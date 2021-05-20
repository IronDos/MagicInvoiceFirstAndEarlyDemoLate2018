<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodBankTransfer extends Model
{
    protected $table = 'payment_method_bank_transfers';

    public $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'date',
        'bankId',
        'bankBranchId',
        'bankAccountId',
        'paymentMethodTotal',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
