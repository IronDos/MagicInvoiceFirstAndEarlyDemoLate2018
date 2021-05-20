@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <h4>פרטי קבלה</h4>
                    <ul>
                        @if (isset($receipt->invoice->id))
                            <li>שיוך לחשבונית:
                                <a href="/businesses/{{$receipt->business->id}}/invoices/{{$receipt->invoice->id}}">
                                    {{$receipt->invoice->id}}
                                </a>
                            </li>    
                        @endif
                        <li>תאריך: {{$receipt->date}}</li>
                        <li>שם הלקוח: {{$receipt->customer->name}}</li>
                    </ul>

                    <ul>
                        <li>תיאור: {{$receipt->description}}</li>
                        <li>סוג מטבע: {{$receipt->currency->name}} {{$receipt->currency->symbol}}</li>
                        <li>מחיר סופי לתשלום: {{$receipt->totalPrice}}</li>
                        <li>הערות: {{$receipt->notes}}</li>
                    </ul>
                    <h4>אמצעי תשלום</h4>
                    
                    @foreach ($receipt->payment->paymentMethodCashes as $cash)
                        <ul>
                            <li>אמצעי תשלום: מזומן</li>
                            <li>תאריך: {{ $cash->date }}</li>
                            <li>סכום כולל: {{ $cash->paymentMethodTotal }}</li>
                        </ul>
                    @endforeach

                    @foreach ($receipt->payment->paymentMethodCreditCards as $creditCard)
                        <ul>
                            <li>אמצעי תשלום: כרטיס אשראי</li>
                            <li>תאריך: {{ $creditCard->date }}</li>
                            <li>סוג כרטיס אשראי: {{ $creditCard->creditCardType }}</li>
                            <li>4 ספרות אחרונות: {{ $creditCard->creditCardLastFourNumbers }}</li>
                            <li>סוג העסקה: {{ $creditCard->creditCardTransactionType }}</li>
                            <li>מספר תשלומים: {{ $creditCard->installmentNumber }}</li>
                            <li>סכום כולל: {{ $creditCard->paymentMethodTotal }}</li>
                        </ul>
                    @endforeach

                    @foreach ($receipt->payment->paymentMethodBankTransfers as $bankTransfer)
                        <ul>
                            <li>אמצעי תשלום: העברה בנקאית</li>
                            <li>תאריך: {{ $bankTransfer->date }}</li>
                            <li>מספר בנק: {{ $bankTransfer->bankId }}</li>
                            <li>מספר סניף: {{ $bankTransfer->bankBranchId }}</li>
                            <li>מספר חשבון בנק: {{ $bankTransfer->bankAccountId }}</li>
                            <li>סכום כולל: {{ $bankTransfer->paymentMethodTotal }}</li>
                        </ul>
                    @endforeach

                    @foreach ($receipt->payment->paymentMethodCheques as $cheque)
                        <ul>
                            <li>אמצעי תשלום: צ'ק</li>
                            <li>תאריך: {{ $cheque->date }}</li>
                            <li>מספר בנק: {{ $cheque->bankId }}</li>
                            <li>מספר סניף: {{ $cheque->bankBranchId }}</li>
                            <li>מספר חשבון בנק: {{ $cheque->bankAccountId }}</li>
                            <li>מספר הצ'ק: {{ $cheque->chequeId }}</li>
                            <li>סכום כולל: {{ $cheque->paymentMethodTotal }}</li>
                        </ul>
                    @endforeach

                    <h6>סכום כולל של כל התשלומים: {{ $receipt->payment->paymentTotal}}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection