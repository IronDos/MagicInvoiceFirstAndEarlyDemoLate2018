<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocsNumbering extends Model
{
    protected $table = 'docs_numberings';

    public $primaryKey = 'id';

    protected $fillable = [
        'business_id',
        'Draft',
        'DraftVAT',
        'Invoice',
        'InvoiceVAT',
        'Receipt',
        'ReceiptVAT',
        'InvoiceReceipt',
        'InvoiceVATReceipt',
        'CreditInvoice',
        'ShippingCertificate',
        'ReturnCertificate',
        'Order'
    ];

    public function business()
    {
        return $this->belongsTo('App\Business');
    }

    public function docsCounter($invoiceTypeName)
    {
        switch ($invoiceTypeName) {
            case 'Draft':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'DraftVAT':
                
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'Invoice':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'InvoiceVAT':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'Receipt':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'InvoiceReceipt':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'InvoiceVATReceipt':
                $invoices = $this->GetInvoicesByTypeName($invoiceTypeName);
                return $this->GetMaxDocNumber($invoices, $invoiceTypeName);
                break;
            case 'Receipt':
                if ($this->business->receipts->count() > 0) {
                    return $this->business->receipts->max('docNumber');
                }
                return "{$this->$typeName}";
                break;
            default:
                return null;
                break;
        }
        
    }

    public function GetInvoicesByTypeName($invoiceTypeName)
    {
        return $this->business->invoices->filter(function($invoice) use($invoiceTypeName) {
            if ($invoice->invoiceType->name == $invoiceTypeName) {
                return $invoice;
            }
        });
    }

    private function GetMaxDocNumber($docs, $typeName)
    {
        if ($docs->count() > 0) {
            return $docs->max('docNumber');
        }
        return "{$this->$typeName}";
    }

    public function GetDisabledDocs()
    {
        $disabled = [];
        if ($this->business->user->plan->bType == 'ExemptDealer') {
            $drafts = $this->GetInvoicesByTypeName('Draft');
            $invoices = $this->GetInvoicesByTypeName('Invoice');
            $invoiceReceipts = $this->GetInvoicesByTypeName('InvoiceReceipt');
            $receipts = $this->business->Receipts;

            if ($drafts->count() > 0) { array_push($disabled, ['Draft' => true]); }
            if ($invoices->count() > 0) { array_push($disabled, 'Invoice'); }
            if ($invoiceReceipts->count() > 0) { array_push($disabled, 'InvoiceReceipt'); }
            if ($receipts->count() > 0) { array_push($disabled, 'Receipt'); }
        }

        if ($this->business->user->plan->bType == 'AuthorizedDealer') {
            $drafts = $this->GetInvoicesByTypeName('DraftVAT');
            $invoices = $this->GetInvoicesByTypeName('InvoiceVAT');
            $invoiceReceipts = $this->GetInvoicesByTypeName('InvoiceVATReceipt');
            $receipts = $this->business->Receipts;

            if ($drafts->count() > 0) { $disabled['DraftVAT'] = true; }
            if ($invoices->count() > 0) { array_push($disabled, 'InvoiceVAT'); }
            if ($invoiceReceipts->count() > 0) { array_push($disabled, 'InvoiceVATReceipt'); }
            if ($receipts->count() > 0) { array_push($disabled, 'Receipt'); }
        }

        return $disabled;
    }
}
