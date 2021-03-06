@extends("agent.layouts.app")

@section("css")

@endsection

@section('toolbar')
    <!--begin::Page title-->
    <div class="page-title d-flex justify-content-center flex-column me-5">
        <!--begin::Title-->
        <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Clients</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('agent.dashboard') }}"
                   class="text-muted text-hover-primary">Agence: {{ auth()->user()->agency->name }}</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('agent.loan.all') }}" class="text-muted text-hover-primary">Pret Bancaire</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-dark">{{ $loan->reference }} - {{ $loan->plan->name }}</li>
            <!--end::Item-->
        </ul>
        <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
@endsection

@section("content")
    <div class="container-fluid">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-15">
                        <!--begin::Summary-->
                        <div class="d-flex flex-center flex-column mb-5">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-100px symbol-circle mb-7">
                                <i class="fa-solid fa-building fa-4x"></i>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Name-->
                            <a href="#"
                               class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $loan->reference }}</a>
                            <!--end::Name-->
                            <!--begin::Position-->
                            <div
                                class="fs-5 fw-bold text-muted mb-6">{{ $loan->plan->name }}</div>
                            <!--end::Position-->
                            {!! \App\Helper\CustomerLoanHelper::getStatusLoan($loan->status) !!}
                        </div>
                        <!--end::Summary-->
                        <!--begin::Details toggle-->
                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                 href="#kt_customer_view_details" role="button" aria-expanded="false"
                                 aria-controls="kt_customer_view_details">Details
                                <span class="ms-2 rotate-180">
									<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
									<span class="svg-icon svg-icon-3">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
											<path
                                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                fill="currentColor"></path>
										</svg>
									</span>
								</span>
                            </div>
                            <!--<span data-bs-toggle="tooltip" data-bs-trigger="hover" title="" data-bs-original-title="Edit customer details">
								<a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">Edit</a>
							</span>-->
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator separator-dashed my-3"></div>
                        <!--begin::Details content-->
                        <div id="kt_customer_view_details" class="collapse show">
                            <div class="py-5 fs-6">
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Montant demander</div>
                                <div class="text-gray-600">{{ eur($loan->amount_loan) }}</div>
                                <!--begin::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Montant du</div>
                                <div class="text-gray-600">{{ eur($loan->amount_du) }}</div>
                                <!--begin::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Mensualit??</div>
                                <div class="text-gray-600">{{ eur($loan->mensuality) }} / par mois</div>
                                <!--begin::Details item-->

                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Dur??e</div>
                                <div class="text-gray-600">{{ $loan->duration }} mois</div>
                                <!--begin::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Jours de pr??l??vement</div>
                                <div class="text-gray-600">{{ $loan->prlv_day }} de chaque mois</div>

                                <div class="fw-bolder mt-5">Client</div>
                                <div class="d-flex align-items-center mb-7">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-50px me-5">
                                        <div class="symbol-label fs-2 fw-bold text-{{ random_color() }}">{{ Str::limit($loan->customer->info->firstname, 2, '') }}</div>
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Text-->
                                    <div class="flex-grow-1">
                                        <a href="{{ route('agent.customer.show', $loan->customer->id) }}" class="text-dark fw-bolder text-hover-primary fs-6">{{ \App\Helper\CustomerHelper::getName($loan->customer) }}</a>
                                        <span class="text-muted d-block fw-bold">{!! \App\Helper\CustomerHelper::getTypeCustomer($loan->customer->info->type, true) !!}</span>
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--begin::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Compte de pr??l??vement</div>
                                <a href="{{ route('agent.customer.wallet.show', [$loan->customer->id, $loan->payment->id]) }}" class="text-gray-600">{{ $loan->payment->number_account }}</a>
                                <!--begin::Details item-->
                            </div>
                        </div>
                        <!--end::Details content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <div class="flex-lg-row-fluid ms-lg-15">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Pret bancaire N??{{ $loan->reference }}</h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-light" data-kt-menu-trigger="hover" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
                                Action
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bolder px-3 py-4">Mon pret</div>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu separator-->
                                <div class="separator mb-3 opacity-75"></div>
                                <!--end::Menu separator-->

                            @if($loan->status == 'open')
                                <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#startCheckLoan" data-bs-toggle="modal" class="menu-link text-danger px-3">
                                            Lancer la v??rification du dossier
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                            @else

                                <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#edit_date_loan" data-bs-toggle="modal" class="menu-link px-3">
                                            Modifier la date de pr??l??vement
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#report_loan" data-bs-toggle="modal" class="menu-link px-3">
                                            Report d'??ch??ance
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('agent.customer.wallet.loan.table', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" target="_blank" class="menu-link px-3">
                                            Tableau d'amortissement
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#edit_cpt_loan" data-bs-toggle="modal" class="menu-link px-3">
                                            Compte de pr??l??vement
                                        </a>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#remb_loan" data-bs-toggle="modal" class="menu-link px-3">
                                            Remboursement par anticipation
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                            @endif

                            <!--begin::Menu item-->
                                <div class="menu-item px-3 pb-3">
                                    <a href="#edit_status_loan" data-bs-toggle="modal" class="menu-link px-3">
                                        Editer le status du pret
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="liste_transaction">
                            <!--begin::Table head-->
                            <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Date</th>
                                <th class="min-w-125px">Libell??e</th>
                                <th class="min-w-125px">D??bit</th>
                                <th class="min-w-125px">Cr??dit</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                            @foreach($loan->wallet->transactions()->where('type', 'sepa')->where('type', 'facelia')->get() as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->designation }}</td>
                                    <td>
                                        @if($transaction->amount < 0)
                                            {{ eur($transaction->amount) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->amount > 0)
                                            {{ eur($transaction->amount) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <div class="card-footer">
                        Footer
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="startCheckLoan">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">V??rification du dossier de pret</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <p>
                        Le syst??me va maintenant v??rifier certaine source afin de voir si le dossier de pret N?? {{ $loan->reference }} est acceptable.<br>
                        A la fin de la v??rification une echelle de 0 ?? 10 vous sera indiquer pour 0 (Dangereux) et 10 (Sur).
                    </p>
                    <div class="changelogCheckLoan bg-gray-300 p-5 d-none">
                        <div class="status-code fw-bolder fs-2x text-center">8</div>
                        <div class="status-text fw-bolder fs-1x text-center">Dossier Sur</div>
                        <div class="status-result"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-bank" data-url="{{ route('agent.customer.wallet.loan.check', [$loan->customer_id, $loan->payment->id, $loan->id]) }}">
                        <span class="indicator-label">
                            Lancer la v??rification
                        </span>
                        <span class="indicator-progress">
                            Veuillez patienter... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="edit_status_loan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">Changement du status du pret bancaire</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="formEditStatusLoan" action="{{ route('agent.customer.wallet.loan.status', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered mb-10">
                            <tbody>
                            <tr>
                                <td>N?? Pret</td>
                                <td id="check_reference">{{ $loan->reference }}</td>
                            </tr>
                            <tr>
                                <td>Etat actuelle</td>
                                <td id="loan_actual_status">{!! \App\Helper\CustomerLoanHelper::getStatusLoan($loan->status) !!}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mb-10">
                            <label for="status">Prochaine Etat du pret</label>
                            <select name="status" id="status" class="form-select form-select-solid" data-parent="#edit_status_loan" data-control="select2" data-placeholder="Selectionner un status">
                                <option value=""></option>
                                <option value="open" @if($loan->status == 'open') selected @endif>Nouveau Dossier</option>
                                <option value="study" @if($loan->status == 'study') selected @endif>Traitement de la demande</option>
                                <option value="accepted" @if($loan->status == 'accepted') selected @endif>Accepter</option>
                                <option value="refused" @if($loan->status == 'refused') selected @endif>Refuser</option>
                                <option value="progress" @if($loan->status == 'progress') selected @endif>Utilisation en cours</option>
                                <option value="terminated" @if($loan->status == 'terminated') selected @endif>Pret rembours??</option>
                                <option value="error" @if($loan->status == 'error') selected @endif>Erreur</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <x-form.button />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="edit_date_loan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">Changement de la date de Pr??l??vement</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="formEditDateLoan" action="{{ route('agent.customer.wallet.loan.date', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered mb-10">
                            <tbody>
                            <tr>
                                <td>N?? Pret</td>
                                <td id="check_reference">{{ $loan->reference }}</td>
                            </tr>
                            <tr>
                                <td>Date de Pr??l??vement Actuelle</td>
                                <td id="loan_actual_status">{{ $loan->prlv_day }} de chaque mois</td>
                            </tr>
                            </tbody>
                        </table>
                        <x-form.input
                            name="prlv_day"
                            type="text"
                            label="Date de Pr??l??vement futur"
                            help="true"
                            helpText="La date entr??e dans ce champs entrera en vigueur le mois suivant" />
                    </div>

                    <div class="modal-footer">
                        <x-form.button />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="edit_cpt_loan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">Changement du compte de pr??l??vement</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="formEditCptLoan" action="{{ route('agent.customer.wallet.loan.compte', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered mb-10">
                            <tbody>
                            <tr>
                                <td>N?? Pret</td>
                                <td id="check_reference">{{ $loan->reference }}</td>
                            </tr>
                            <tr>
                                <td>Compte de pr??l??vement actuel</td>
                                <td id="loan_actual_status">Compte Ch??que N??{{ $loan->payment->number_account }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mb-10">
                            <label for="wallet_payment_id">Nouveau compte de pr??l??vement</label>
                            <select id="wallet_payment_id" name="wallet_payment_id" class="form-select form-select-solid" data-parent="#edit_cpt_loan" data-control="select2" data-placeholder="Selectionner un nouveau compte">
                                <option value=""></option>
                                @foreach(\App\Models\Customer\CustomerWallet::where('customer_id', $loan->customer_id)->where('type', 'compte')->where('status', 'active')->where('id', '!=', $loan->payment->id)->get() as $wallet)
                                    <option value="{{ $wallet->id }}">Compte Ch??que N??{{ $wallet->number_account }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <x-form.button />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="report_loan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">Report d'??ch??ance</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="formReportLoan" action="{{ route('agent.customer.wallet.loan.report', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered mb-10">
                            <tbody>
                            <tr>
                                <td>N?? Pret</td>
                                <td id="check_reference">{{ $loan->reference }}</td>
                            </tr>
                            <tr>
                                <td>Prochaine Ech??ance</td>
                                <td id="loan_actual_status">{{ \Carbon\Carbon::create(now()->year, now()->addMonth()->month, $loan->prlv_day)->format('d/m/Y') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <x-form.button text="Reporter l'??ch??ance"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="remb_loan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-bank">
                    <h5 class="modal-title text-white">Remboursement par anticipation du pret n??{{ $loan->reference }}</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times fa-2x text-white"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="formRembLoan" action="{{ route('agent.customer.wallet.loan.remb', [$loan->customer_id, $loan->payment->id, $loan->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered mb-10">
                            <tbody>
                            <tr>
                                <td>N?? Pret</td>
                                <td id="check_reference">{{ $loan->reference }}</td>
                            </tr>
                            <tr>
                                <td>Montant Restant du</td>
                                <td id="loan_actual_status">{{ \App\Helper\CustomerLoanHelper::calcRestantDu($loan) }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <x-form.input
                            name="amount"
                            type="text"
                            label="Montant ?? rembours??" />
                    </div>

                    <div class="modal-footer">
                        <x-form.button />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section("script")
    @include("agent.scripts.loan.show")
@endsection
