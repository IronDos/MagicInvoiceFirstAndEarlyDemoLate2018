<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodCreditCard extends Model
{
    protected $table = 'payment_method_credit_cards';

    public $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'date',
        'creditCardType',
        'creditCardLastFourNumbers',
        'creditCardTransactionType',
        'installmentNumber',
        'paymentMethodTotal',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
