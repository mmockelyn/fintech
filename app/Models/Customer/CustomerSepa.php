<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSepa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wallet()
    {
        return $this->belongsTo(CustomerWallet::class, 'customer_wallet_id');
    }

    public function creditor()
    {
        return $this->hasMany(CustomerCreditor::class);
    }
}
