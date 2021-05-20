<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodCheque extends Model
{
    protected $table = 'payment_method_cheques';

    public $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'date',
        'bankId',
        'bankBranchId',
        'bankAccountId',
        'chequeId',
        'paymentMethodTotal',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
