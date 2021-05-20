<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\InvoiceType;

class InvoiceTypeValidation implements Rule
{
    protected $errors;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        foreach ($value as $key => $invoiceType) {
            if (isset($invoiceType['id'])) {
                
                $tempInvoiceType = InvoiceType::find($invoiceType['id']);
            
                if ($tempInvoiceType != null) {
                    if ($tempInvoiceType->bType != Auth::user()->plan->bType)
                    {
                        $this->errors = 'אחד מסוגי המסמכים אינו חוקי';
                        return false;
                    }
                } else {
                    $this->errors = 'אחד מסוגי המסמכים אינו חוקי';
                    return false;
                }
            }
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
