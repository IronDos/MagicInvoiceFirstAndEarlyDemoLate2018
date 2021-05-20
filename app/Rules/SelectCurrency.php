<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Currency;

class SelectCurrency implements Rule
{
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
        $currencies = Currency::all();
        foreach ($currencies as $currency) {
            if ($currency->name == $value) {return true;}
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
        return 'המטבע אינו תקין';
    }
}
