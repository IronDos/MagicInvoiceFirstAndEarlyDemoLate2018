<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Payment;
use App\PaymentMethodCreditCard;
use App\PaymentMethodCheque;
use App\PaymentMethodBankTransfer;
use App\PaymentMethodCash;

class PaymentsController extends Controller
{
    public static function store($payments, $paymentsTotal) {
        $payment = Payment::create([
            'paymentTotal' => $paymentsTotal,
        ]);

        $paymentId = $payment->id;

        //$payments = $request->input('payments');

        foreach ($payments as $key => $paymentMethod) {
            if ($paymentMethod['selectMethod'] == 'CreditCard') {
                PaymentMethodCreditCard::create([
                    'payment_id' => $paymentId,
                    'date' => $paymentMethod['date'],
                    'creditCardType' => $paymentMethod['selectCreditCardType'],
                    'creditCardLastFourNumbers' => (int)$paymentMethod['creditCardLastFourNumbers'],
                    'creditCardTransactionType' => $paymentMethod['creditCardTransactionType'],
                    'installmentNumber' => $paymentMethod['installmentNumber'],
                    'paymentMethodTotal' => $paymentMethod['paymentTotal'],
                ]);
            }

            if ($paymentMethod['selectMethod'] == 'Cheque') {
                
                $chequeId = (string)$paymentMethod['chequeId'];
                // return dd($chequeId);
                PaymentMethodCheque::create([
                    'payment_id' => $paymentId,
                    'date' => $paymentMethod['date'],
                    'bankId' => $paymentMethod['bankId'],
                    'bankBranchId' => $paymentMethod['bankBranchId'],
                    'bankAccountId' => $paymentMethod['bankAccountId'],
                    'chequeId' => $paymentMethod['chequeId'],
                    'paymentMethodTotal' => $paymentMethod['paymentTotal'],
                ]);
            }

            if ($paymentMethod['selectMethod'] == 'BankTransfer') {
                PaymentMethodBankTransfer::create([
                    'payment_id' => $paymentId,
                    'date' => $paymentMethod['date'],
                    'bankId' => $paymentMethod['bankId'],
                    'bankBranchId' => $paymentMethod['bankBranchId'],
                    'bankAccountId' => $paymentMethod['bankAccountId'],
                    'paymentMethodTotal' => $paymentMethod['paymentTotal'],
                ]);
            }

            if ($paymentMethod['selectMethod'] == 'Cash') {
                PaymentMethodCash::create([
                    'payment_id' => $paymentId,
                    'date' => $paymentMethod['date'],
                    'paymentMethodTotal' => $paymentMethod['paymentTotal'],
                ]);
            }
            
        }
        // End ForEach loop

        return $paymentId;
    }
}
