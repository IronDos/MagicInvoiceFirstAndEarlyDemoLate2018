<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InvoiceTotalPriceAndPaymentsVaildation implements Rule
{
    protected $invoiceTotalPrice;
    protected $errors;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($invoiceTotalPrice)
    {
        $this->invoiceTotalPrice = $invoiceTotalPrice;
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
        if ($value != $this->invoiceTotalPrice) {
            $this->errors = 'הסכום הכולל של המסמך אינו תואם את זה של התשלומים';
        }
        if ($this->errors == '') { return true; }
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
