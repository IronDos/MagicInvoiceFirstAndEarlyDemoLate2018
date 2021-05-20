<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    public $primaryKey = 'id';
    
    protected $fillable = [
        'invoice_id',
        'paymentTotal',
    ];

    public function paymentMethodCreditCards()
    {
        return $this->hasMany('App\PaymentMethodCreditCard');
    }

    public function paymentMethodCheques()
    {
        return $this->hasMany('App\PaymentMethodCheque');
    }

    public function paymentMethodBankTransfers()
    {
        return $this->hasMany('App\PaymentMethodBankTransfer');
    }

    public function paymentMethodCashes()
    {
        return $this->hasMany('App\PaymentMethodCash');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
}
