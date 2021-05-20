<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;
use App\Customer;
use App\Business;


class CustomerValidation implements Rule
{
    protected $business;
    protected $customerType;
    protected $customerName;
    protected $msgError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($business, $customerType, $customerName)
    {
        $this->customerType = $customerType;
        $this->customerName = $customerName;
        $this->business = Business::find($business);
        if ($this->business != null) { $this->business = $this->business->first(); }
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
        if ($this->customerType == 'NewCustomer') {
            // Start Check CustomerName
            $validator = Validator::make(
                ['customerName' => $this->customerName],
                ['customerName' => 'required|string|min:1|max:250']

            );
            if ($validator->fails()) {
                $this->msgError = 'cN';
            } else {
                $customer = Customer::where('name', $this->customerName)->first();
                if ($customer == null) {
                    return true;
                } else {
                    $this->msgError = 'cN';
                    return false;
                }
            }
            // End Check CustomerName  
        } elseif ($this->customerType == 'ExistingCustomer') {
            $customer = Customer::find($value);
            if ($customer != null && $this->business != null) {
                $this->business->first();
                $customer->first();
                if ($this->business->id == $customer->business->id) {
                    return true;
                }
                else {
                    $this->msgError = 'לקוח אינו תקין';
                }
            }
        } elseif ($this->customerType == 'notRequired') {
            if ($value == '') {
                return true;
            } else {
                $customer = Customer::find($value);
                if ($customer != null && $this->business != null) {
                    $this->business->first();
                    $customer->first();
                    if ($this->business->id == $customer->business->id) {
                        return true;
                    }
                    else {
                        $this->msgError = 'לקוח אינו תקין';
                    }
                }
            }
        } else {
            $this->msgError = 'נא מלא לקוח';
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
