<?php

namespace App\Http\Controllers\Agent;

use App\Helper\CustomerLoanHelper;
use App\Helper\CustomerTransactionHelper;
use App\Helper\LogHelper;
use App\Http\Controllers\Controller;
use App\Jobs\Agent\Customer\RembLoan;
use App\Jobs\Agent\Customer\ReportScheduleLoan;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerPret;
use App\Models\Customer\CustomerSepa;
use App\Models\Customer\CustomerWallet;
use App\Notifications\Agent\Customer\UpdateStatusLoanNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerLoanController extends Controller
{
    public function all()
    {
        $loans = CustomerPret::all();

        return view('agent.loan.index', compact('loans'));
    }

    public function show($loan)
    {
        $loan = CustomerPret::find($loan);

        return view('agent.loan.show', compact('loan'));
    }

    /**
     * Vérification Primaire de la demande de pret
     * @param $customer
     * @param $wallet
     * @param $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function check($customer, $wallet, $loan)
    {
        $v = 0;
        $loan = CustomerPret::find($loan);
        $wallet = CustomerWallet::find($loan->wallet_payment_id);
        $customer = $wallet->customer;
        $text = collect();

        /*
         * Vérification des transactions du compte bancaire principal
         * NB: On calcul la moyenne des débits et des crédit si crédit > débit V+1
         */

        $deb = $wallet->transactions()->where('confirmed', true)->where('amount', '<', 0)->avg('amount');
        $cred = $wallet->transactions()->where('confirmed', true)->where('amount', '>', 0)->avg('amount');

        $cred > $deb ? $v++ : $v--;
        if ($cred > $deb) {
            $v++;
        } else {
            $v--;
            $text->add(['transactions']);
        }

        /*
         * Vérification du nombre de pret bancaire
         * Si == 0 $v++
         */

        $loans = $customer->prets()->where('status', 'terminated')->count();
        $loans == 0 ? $v++ : $v--;
        if ($loans == 0) {
            $v += 2;
        } elseif ($loans >= 1 && $loans <= 3) {
            $v++;
        } else {
            $v--;
            $text->add(['loans']);
        }

        /*
         * Vérifie si le client à déja un découvert
         * si oui $v--
         */

        $wallet->decouvert == 1 ? $v-- : $v++;
        if ($wallet->decouvert == 1) {
            $v--;
            $text->add(['decouvert']);
        }


        /*
         * Vérifie le salaire du client
         * si <= 500 = $v--
         * si > 500 & <= 1500 = $v++;
         * si > 1500 = $v += 2;
         */

        if ($customer->income->pro_incoming <= 500) {
            $v--;
            $text->add(['incoming']);
        } elseif ($customer->income->pro_incoming > 500 && $customer->income->pro_incoming <= 1500) {
            $v++;
        } else {
            $v += 2;
        }

        /*
         * Vérification de la cotation client
         */

        if ($customer->cotation <= 4) {
            $v--;
            $text->add(['cotation']);
        } elseif ($customer->cotation > 4 && $customer->cotation <= 6) {
            $v++;
        } else {
            $v += 2;
        }

        /*
         * Vérification FICP
         */

        if ($customer->ficp == true) {
            $v--;
            $text->add(['ficp']);
        } else {
            $v++;
        }

        $result = $v * 2;

        return response()->json([
            "resultat" => $result,
            "text" => $text
        ]);

    }

    /**
     * Mise à jour du status du pret manuel
     * @param Request $request
     * @param $customer
     * @param $wallet
     * @param $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request, $customer, $wallet, $loan)
    {
        try {
            $loan = CustomerPret::find($loan);
            //dd($loan->wallet);

            $loan->update([
                'status' => $request->get('status')
            ]);

            /*
             * si le pret est passer à accepter
             */

            if ($request->get('status') == 'accepted') {
                $loan->wallet->update([
                    'status' => 'active'
                ]);

                CustomerTransactionHelper::create('credit',
                    'autre',
                    'Attribution de la somme du pret N°' . $loan->reference,
                    $loan->amount_loan,
                    $loan->wallet->id,
                    false,
                    'Pret N°' . $loan->reference);
            }

            if ($request->get('status') == 'refused') {
                $loan->wallet->update([
                    'status' => 'closed'
                ]);
            }

            // Notification
            auth()->user()->notify(new UpdateStatusLoanNotification($loan->customer, $loan, $request->get('status')));
            $loan->customer->user->notify(new \App\Notifications\Customer\UpdateStatusLoanNotification($loan->customer, $loan, $request->get('status')));

            return response()->json();
        } catch (\Exception $exception) {
            LogHelper::notify('critical', $exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Modifie la date de prélèvement d'un pret bancaire
     * @param Request $request
     * @param $customer
     * @param $wallet
     * @param $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function date(Request $request, $customer, $wallet, $loan)
    {
        $loan = CustomerPret::find($loan);

        try {
            $loan->update([
                'prlv_day' => $request->get('prlv_day')
            ]);

            return response()->json();
        } catch (\Exception $exception) {
            LogHelper::notify('critical', $exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Reporte la prochaine échéance d'un pret bancaire
     *
     * @param Request $request
     * @param $customer
     * @param $wallet
     * @param $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(Request $request, $customer, $wallet, $loan)
    {
        $loan = CustomerPret::find($loan);
        $date_prlv = Carbon::create(now()->year, now()->addMonth()->month, $loan->prlv_day);
        try {
            dispatch(new ReportScheduleLoan($loan, $date_prlv))->delay($date_prlv);

            return response()->json(['nextDate' => $date_prlv->addMonth()->format('d/m/Y')]);
        } catch (\Exception $exception) {
            LogHelper::notify('critical', $exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Changement du compte de prélèvement d'un pret bancaire
     *
     * @param Request $request
     * @param $customer
     * @param $wallet
     * @param $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function compte(Request $request, $customer, $wallet, $loan)
    {
        $loan = CustomerPret::find($loan);

        try {
            $loan->update([
                'wallet_payment_id' => $request->get('wallet_payment_id')
            ]);

            return response()->json();
        }catch (\Exception $exception) {
            LogHelper::notify('critical', $exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }

    public function remb(Request $request, $customer, $wallet, $loan)
    {
        $loan = CustomerPret::find($loan);

        try {
            $restant = CustomerLoanHelper::calcRestantDu($loan, false);

            if($request->get('amount') > $restant) {
                return response()->json(['error' => "Le montant instruit est supérieur au restant dù"], 500);
            } else {
                $sepa = CustomerSepa::create([
                    'uuid' => \Str::uuid(),
                    'creditor' => config('app.name'),
                    'number_mandate' => \Str::upper(\Str::random(8)),
                    'amount' => - $request->get('amount'),
                    'status' => 'waiting',
                    'created_at' => now(),
                    'updated_at' => now()->addDay(),
                    'customer_wallet_id' => $loan->wallet_payment_id
                ]);

                dispatch(new RembLoan($loan, $sepa))->delay(now()->addDay()->startOfDay());

                return response()->json();
            }
        }catch (\Exception $exception) {
            LogHelper::notify('critical', $exception->getMessage());
            return response()->json($exception->getMessage());
        }
    }

}
