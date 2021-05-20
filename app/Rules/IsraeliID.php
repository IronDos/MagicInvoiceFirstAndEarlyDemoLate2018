<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsraeliID implements Rule
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
        $id = (string)$value;
        if (strlen($id) != 9) {return false;}
        $id_check = [1,2,1,2,1,2,1,2,1];
        $id_multiply  = [];
        $id_sum = 0;
        for ($i=0; $i < 9; $i++) { 
            $id_multiply[$i] = $id[$i] * $id_check[$i];
            if ($id_multiply[$i]>9) {
                $id_sum += substr($id_multiply[$i], 0, 1) + substr($id_multiply[$i], -1);
            } else {
                $id_sum += $id_multiply[$i];
            }
        }

        if (is_int($id_sum/10)) { return true; }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'תעודת הזהות אינה תקינה';
    }
}
