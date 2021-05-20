<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DiscountValidation implements Rule
{
    protected $discountType;
    protected $discountTypeErrors;
    protected $discountAmountErrors;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($discountType)
    {
        //return dd($discountType);
        $this->discountType = $discountType;
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
        if ($this->discountType == 'Money') {
            return true;
        } elseif ($this->discountType == 'Percentage') {
            if ($value > 100 || $value < 0 ) {
                $this->discountAmountErrors = 'הנחה באחוזים מ1-100';    
            }
        } else {
            $this->discountTypeErrors = 'סוג הנחנה אינו תקין';
        }

        if ($this->discountTypeErrors == null &&
            $this->discountAmountErrors == null) { return true; }
        else { return false; }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            $this->discountTypeErrors,
            $this->discountAmountErrors,
        ];
    }
}
