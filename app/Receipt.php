<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    public $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'customer_record_id',
        'business_id',
        'currency_id',
        'invoice_id',
        'docNumber',
        'date',
        'description',
        'totalPrice',
        'notes'
    ];

    // FKs
    public function payment() { return $this->belongsTo('App\Payment'); }

    public function business() { return $this->belongsTo('App\Business'); }

    public function receiptType() { return $this->belongsTo('App\InvoiceType'); }

    public function customerRecord() { return $this->belongsTo('App\CustomerRecord'); }

    public function currency() { return $this->belongsTo('App\Currency'); }

    public function invoice() { return $this->belongsTo('App\Invoice'); }
}
