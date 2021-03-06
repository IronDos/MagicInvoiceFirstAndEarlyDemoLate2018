<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PlansAndBType implements Rule
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
        if ($value[0] != "") {
            if ($value[0] == "ExemptDealer") {
                if ($value[1] == "DocsOnly" || $value[1] == "DocsAndReports") {
                    return true;
                }
            }

            if ($value[0] == "AuthorizedDealer") {
                if ($value[2] == "DocsOnly" || $value[2] == "DocsAndReports" || $value[2] == "DocReportsAndRepresentation") {
                    return true;
                }
            }
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
        return 'סוג העסק / חבילות אינו תקין';
    }
}
