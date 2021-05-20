<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'businesses';

    public $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'businessTaxIdNumber',
        'name',
        'email',
        'phone',
        'fax',
        'city',
        'address',
        'website',
        'subTitle',
        'currency_id',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function receipts()
    {
        return $this->hasMany('App\Receipt');
    }

    public function docsNumberings()
    {
        return $this->hasMany('App\DocsNumbering');
    }

    public function OpenInvoices()
    {
        $openInvoice=[];

        foreach ($this->invoices as $key => $invoice) {
            if ($invoice->invoiceType->name == 'Invoice' ||
                $invoice->invoiceType->name == 'InvoiceVAT') {
                    $tempInvoice = [
                        'id' => $invoice->id,
                        'customerName' => $invoice->customer->name,
                        'totalPrice' => $invoice->totalPrice
                    ];
                    $openInvoice[] = $tempInvoice;
            }
        }
        return $openInvoice;
    }

    public function VATReport($startDate, $endDate)
    {
        $closedInvoices = $this->invoices->filter(function($invoice) use($startDate, $endDate) {
            if ($invoice->date >= $startDate && $invoice->date <= $endDate) {
                return $invoice->invoiceStatus->status == 'Closed';
            }
        });

        $totalPriceBeforeVAT = 0;
        $VAT = 0;
        $totalPriceWithoutVAT = 0;

        foreach ($closedInvoices as $key => $closedInvoice) {
            if ($closedInvoice->totalPriceBeforeVAT != $closedInvoice->totalPrice)
            {
                $totalPriceBeforeVAT += $closedInvoice->totalPriceBeforeVAT;
            } else {
                $totalPriceWithoutVAT += $closedInvoice->totalPrice;
            }
            
            $VAT += $closedInvoice->totalPrice - $closedInvoice->totalPriceBeforeVAT;
        }

        return [
            'totalPriceBeforeVAT' => $totalPriceBeforeVAT,
            'VAT' => $VAT,
            'totalPriceWithoutVAT' => $totalPriceWithoutVAT
        ];
    }
}
