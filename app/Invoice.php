<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    public $primaryKey = 'id';

    protected $fillable = [
        'payment_id',
        'customer_record_id',
        'business_id',
        'invoice_type_id',
        'invoice_status_id',
        'currency_id',
        'discount_id',
        'docNumber',
        'date',
        'currencyDate',
        'totalPriceBeforeVAT',
        'vat_id',
        'totalPrice',
        'notes' 
    ];

    // FKs
    public function payment() { return $this->belongsTo('App\Payment'); }

    public function business() { return $this->belongsTo('App\Business'); }

    public function invoiceType() { return $this->belongsTo('App\InvoiceType'); }

    public function invoiceStatus() { return $this->belongsTo('App\InvoiceStatus'); }

    public function customerRecord() { return $this->belongsTo('App\CustomerRecord'); }

    public function currency() { return $this->belongsTo('App\Currency'); }

    public function vat() { return $this->belongsTo('App\VAT'); }
    
    public function discount() { return $this->belongsTo('App\Discount'); }

    // FK in other models
    public function productLineInInvoices() { return $this->hasMany('App\ProductLineInInvoice'); }
    public function receipts() { return $this->hasMany('App\Receipt'); }

    public function GetSumOfPayments()
    {
        if ($this->invoiceType->name == 'Invoice' ||
            $this->invoiceType->name == 'InvoiceVAT') {
                $sum = 0;
                foreach ($this->receipts as $key => $receipt) {
                    $sum += $receipt->totalPrice;
                }
                $sum = $this->totalPrice - $sum;
                return $sum;
        }

        // if ($this->invoiceType->name == 'InvoiceReceipt' ||
        //     $this->invoiceType->name == 'InvoiceVATReceipt') {
        //         return $this->payment->paymentTotal;
        // }

        return 0;
    }
}
