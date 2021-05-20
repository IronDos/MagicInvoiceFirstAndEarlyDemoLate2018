<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Business;
use App\Invoice;

class OpenInvoiceValidation implements Rule
{
    protected $business;
    protected $paymentTotalPrice;
    protected $msgError;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($business, $paymentTotalPrice)
    {
        $this->business = $business;
        $this->paymentTotalPrice = $paymentTotalPrice;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->business != null) {
            $invoice = Invoice::find($value);
            
            if ($invoice != null) {
                
                if ($invoice->invoiceType->name == 'Invoice' ||
                    $invoice->invoiceType->name == 'InvoiceVAT') {
                        if ($this->paymentTotalPrice > $invoice->GetSumOfPayments()) {
                            $this->msgError = 'סכום הקבלה גדול מסכום החשבונית';
                            return false;
                        }
                        else {
                            return true;
                        }
                }
            } else {
                $this->msgError = 'חשבונית לא תקינה';
            }
        } else {
            $this->msgError = 'עסק לא תקין';
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msgError;
    }
}
