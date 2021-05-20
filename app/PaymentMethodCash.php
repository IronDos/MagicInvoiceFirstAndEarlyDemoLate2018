<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodCash extends Model
{
    protected $table = 'payment_method_cashes';
    
    public $primaryKey = 'id';
    
    protected $fillable = [
        'payment_id', 'date', 'paymentMethodTotal', 'payment_id'
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
