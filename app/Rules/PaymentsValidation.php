<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class PaymentsValidation implements Rule
{
    protected $errors;
    protected $selectMethod;
    protected $date;
    protected $creditCardLastFourNumbers;
    protected $selectCreditCardType;
    protected $creditCardTransactionType;
    protected $installmentNumber;
    protected $bankId;
    protected $bankBranchId;
    protected $bankAccountId;
    protected $chequeId;
    protected $paymentTotal;
    protected $paymentsTotal;
    protected $paymentsTotalError;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($paymentsTotal)
    {
        if (is_numeric($paymentsTotal)) {
            if ($paymentsTotal>0) {
                $this->paymentsTotal = $paymentsTotal;
            }
        }
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
        $payments = $value;
        $sum=0;
        // $this->errors[0]['selectMethod'] = 'עד מתי';
        // $this->errors[2]['date'] = 'date Isnt good';
        // if ($this->errors == []) {return true; }
        // else {return false;}
        
        foreach ($payments as $key => $payment) {
            if ($payment['selectMethod'] == '') {
                $this->selectMethod[$key] = 'בחר אמצעי תשלום' ;
            }
            elseif ($payment['selectMethod'] != 'CreditCard' &&
                $payment['selectMethod'] != 'Cheque' &&
                $payment['selectMethod'] != 'BankTransfer' &&
                $payment['selectMethod'] != 'Cash') {
                $this->selectMethod[$key] = 'אמצעי תשלום לא תקין';
            }
            
    
            else {
               // Start Date Check
                $validator = Validator::make(
                    ['date' => $payment['date']],
                    ['date' => 'required|date|after:yesterday']

                );
                if ($validator->fails()) {
                    $this->date[$key] = 'תאריך לא תקין';
                }
                // End Check Date

                else {
                    if ($payment['selectMethod'] == 'CreditCard') {
                        if (!is_integer($payment['creditCardLastFourNumbers']) ||
                            strlen($payment['creditCardLastFourNumbers'])!=4) {
                            $this->creditCardLastFourNumbers[$key] = 'ארבע ספרות אחרונות של כרטיס האשראי אינן תקינות';
                        }

                        if ($payment['selectCreditCardType'] != 'Visa' &&
                            $payment['selectCreditCardType'] != 'AmericanExpress' && 
                            $payment['selectCreditCardType'] != 'MasterCard' &&
                            $payment['selectCreditCardType'] != 'Diners' &&
                            $payment['selectCreditCardType'] != 'Isracard' &&
                            $payment['selectCreditCardType'] != 'Other') {
                            $this->selectCreditCardType[$key] = 'סוג כרטיס אשראי אינו תקין';
                        }

                        if ($payment['creditCardTransactionType'] != 'Regular' &&
                            $payment['creditCardTransactionType'] != 'DeferredCharge' &&
                            $payment['creditCardTransactionType'] != 'Installment' &&
                            $payment['creditCardTransactionType'] != 'Credit' &&
                            $payment['creditCardTransactionType'] != 'Other') {
                            $this->creditCardTransactionType[$key] = 'סוג עסקה לא תקין';
                        }

                        if ($payment['creditCardTransactionType'] == 'Installment')
                        {
                            if (!is_integer($payment['installmentNumber'])) {
                                $this->installmentNumber[$key] = 'מספר תשלומים אינו תקין';
                            }

                            else {
                                if ($payment['installmentNumber']<1) {
                                    $this->installmentNumber[$key] = 'מספר תשלומים אינו תקין';
                                }
                            }
                        }

                    }
                    
                    // Start Check Chque/BankTransfer Methods
                    if ($payment['selectMethod'] == 'Cheque' ||
                        $payment['selectMethod'] =='BankTransfer') {
                        
                        // Start Check BankId
                        if (!is_integer($payment['bankId'])) {
                            $this->bankId[$key] = 'מספר בנק אינו תקין';
                        }
                        elseif ($payment['bankId']<10 || $payment['bankId']>99) {
                            $this->bankId[$key] = 'מספר בנק אינו תקין';
                        }
                        // End Check BankId

                        // Start Check BankBranchId
                        if (!is_integer($payment['bankBranchId'])) {
                            $this->bankBranchId[$key] = 'סניף בנק אינו תקין';
                        }
                        elseif ($payment['bankBranchId']<100 || $payment['bankBranchId']>999) {
                            $this->bankBranchId[$key] = 'סניף בנק אינו תקין';
                        }
                        // End Check BankBranchId
                        
                        // Start Check BankAccountId
                        if (!is_integer($payment['bankAccountId'])) {
                            $this->bankAccountId[$key] = 'חשבון בנק לא תקין';
                        }
                        elseif ($payment['bankAccountId']<10 || $payment['bankAccountId']>99) {
                            $this->bankAccountId[$key] = 'חשבון בנק לא תקין';
                        }
                        // End Check BankAccountId

                        // Start Check ChequeId
                        if ($payment['selectMethod'] == 'Cheque') {
                            if (!is_integer($payment['chequeId'])) {
                                $this->chequeId[$key] = 'צק לא תקין';
                            }
                            elseif ($payment['chequeId']<10 || $payment['chequeId']>99) {
                                $this->chequeId[$key] = 'צק לא תקין';
                            }
                        }
                        // End Check ChequeId
                    }
                    // End Check Chque/BankTransfer Methods
                }
                // Start Check PaymentTotal
                if (!is_numeric($payment['paymentTotal'])) {
                    $this->paymentTotal[$key] = 'סכום לא תקין';
                }
                elseif ($payment['paymentTotal']<1) {
                    $this->paymentTotal[$key] = 'סכום לא תקין';
                }
                else {
                    $sum = $sum + $payment['paymentTotal'];
                }
                
            }
            
            
        }
        // End foreach

        if ($sum != $this->paymentsTotal)
        {
            $this->paymentsTotalError = 'סכום כללי לא תקין';
        }

        if ($this->selectMethod == '' &&
            $this->date == '' &&
            $this->creditCardLastFourNumbers == '' &&
            $this->selectCreditCardType == '' &&
            $this->creditCardTransactionType == '' &&
            $this->installmentNumber == '' &&
            $this->bankId == '' &&
            $this->bankBranchId == '' &&
            $this->bankAccountId == '' &&
            $this->chequeId == '' &&
            $this->paymentTotal == '' &&
            $this->paymentsTotalError == '') {return true;}
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        //return 'The validation error message.';
        return $this->errors = [
            $this->selectMethod,
            $this->date,
            $this->creditCardLastFourNumbers,
            $this->selectCreditCardType,
            $this->creditCardTransactionType,
            $this->installmentNumber,
            $this->bankId,
            $this->bankBranchId,
            $this->bankAccountId,
            $this->chequeId,
            $this->paymentTotal,
            $this->paymentsTotalError
        ];
        // return $this->errors = [
        //     '$this->selectMethod',
        //     '$this->date',
        //     '$this->creditCardLastFourNumbers',
        //     '$this->selectCreditCardType',
        //     '$this->creditCardTransactionType',
        //     '$this->installmentNumber',
        //     '$this->bankId',
        //     '$this->bankBranchId',
        //     '$this->bankAccountId',
        //     '$this->chequeId',
        //     '$this->paymentTotal',
        // ];
    }
}
