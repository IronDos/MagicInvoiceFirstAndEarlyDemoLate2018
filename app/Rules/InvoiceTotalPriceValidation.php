<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Business;

class InvoiceTotalPriceValidation implements Rule
{
    protected $business;
    protected $productsSum;
    protected $invoiceDiscount;
    protected $invoiceTotalPriceBeforeVAT;
    protected $VAT;
    protected $invoiceTotalPrice;
    protected $errors;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
    $business,
    $productsSum,
    $invoiceDiscount,
    $invoiceTotalPriceBeforeVAT,
    $VAT)
    {
        $this->business = $business->first();
        $this->productsSum = $productsSum;
        $this->invoiceDiscount = $invoiceDiscount;
        $this->invoiceTotalPriceBeforeVAT = $invoiceTotalPriceBeforeVAT;
        $this->VAT = $VAT;
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
        $invoiceTotalPrice = $value;
        if (is_numeric($invoiceTotalPrice)) {
            if ($this->business->user->plan->bType == 'AuthorizedDealer') {
                if (round($this->productsSum - ($this->productsSum*$this->invoiceDiscount)) == round($this->invoiceTotalPriceBeforeVAT)) {
                    if (round($this->invoiceTotalPriceBeforeVAT + ($this->invoiceTotalPriceBeforeVAT * $this->VAT)) != round($invoiceTotalPrice)) {
                        $this->errors['invoiceTotalPrice'] = 'סכום כולל של המסמך לא תקין';
                        $this->errors['invoiceTotalPrice'] = $invoiceTotalPrice;
                    }
                } else {
                    $this->errors['invoiceTotalPriceBeforeVAT'] = 'סכום לפני מע"מ לא תקין';
                }
            } elseif ($this->business->user->plan->bType == 'ExemptDealer') {
                if ($this->productsSum - ($this->productsSum*$this->invoiceDiscount) == $this->invoiceTotalPriceBeforeVAT) {
                    if ($this->invoiceTotalPriceBeforeVAT != $invoiceTotalPrice) {
                        $this->errors['invoiceTotalPrice'] = 'סכום כולל של המסמך לא תקין';
                    }
                } else {
                    $this->errors['invoiceTotalPriceBeforeVAT'] = 'סכום לפני מע"מ לא תקין';
                }
            }
        } else {
            $this->errors['invoiceTotalPrice'] = 'סכום כולל של המסמך לא תקין';
        }

        if ($this->errors != '') {
            return false;
        }

        return true;

    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errors;
    }
}
