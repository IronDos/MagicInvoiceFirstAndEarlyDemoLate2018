@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>{{$title}}</h4></div>

                <div class="card-body">
                    <ul>
                        <li>תאריך: {{$invoice->date}}</li>
                        <li>שם הלקוח: <a href="/businesses/{{$business->id}}/customers/{{$invoice->customerRecord->customer->id}}">{{$invoice->customerRecord->name}}</a></li>
                        <li>סוג מסמך: {{ $invoice->invoiceType->title }}</li>                    
                        <li>סטטוס מסמך: {{ $invoice->invoiceStatus->title }}</li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col">שם המוצר</th>
                                    <th scope="col">כמות</th>
                                    <th scope="col">מחיר ליחידה</th>
                                    <th scope="col">מחיר שורה</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productLinesInInvocie as $productLineInInvocie)
                                    <tr>
                                        <th scope="row">
                                            {{ $productLineInInvocie->productRecord->name }}
                                        </th>
                                        <th scope="row">
                                            {{ $productLineInInvocie->quantity }}
                                        </th>
                                        <th scope="row">
                                            {{ $productLineInInvocie->productPrice }}
                                        </th>
                                        <th scope="row">
                                            {{ $productLineInInvocie->totalPrice }}
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <ul>
                        <li>סוג מטבע: {{$invoice->currency->name}} {{$invoice->currency->symbol}}</li>
                        <li>הנחה באחוזים: {{$invoice->discount}}</li>
                        @if ($invoice->invoiceType->bType == 'AuthorizedDealer')
                            <li>מע"מ: {{$invoice->vat->percentage*100}}%</li>
                            <li>מחיר לפני מע"מ: {{$invoice->totalPriceBeforeVAT}}</li>
                        @endif
                        <li>מחיר סופי לתשלום: {{$invoice->totalPrice}}</li>
                        <li>הערות: {{$invoice->notes}}</li>
                    </ul>
                    @if ($invoice->invoiceType->name == 'InvoiceReceipt' ||
                        $invoice->invoiceType->name == 'InvoiceVATReceipt')
                        <h4>אמצעי תשלום</h4>
                        
                        @foreach ($invoice->payment->paymentMethodCashes as $cash)
                            <ul>
                                <li>אמצעי תשלום: מזומן</li>
                                <li>תאריך: {{ $cash->date }}</li>
                                <li>סכום כולל: {{ $cash->paymentMethodTotal }}</li>
                            </ul>
                        @endforeach

                        @foreach ($invoice->payment->paymentMethodCreditCards as $creditCard)
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

                        @foreach ($invoice->payment->paymentMethodBankTransfers as $bankTransfer)
                            <ul>
                                <li>אמצעי תשלום: העברה בנקאית</li>
                                <li>תאריך: {{ $bankTransfer->date }}</li>
                                <li>מספר בנק: {{ $bankTransfer->bankId }}</li>
                                <li>מספר סניף: {{ $bankTransfer->bankBranchId }}</li>
                                <li>מספר חשבון בנק: {{ $bankTransfer->bankAccountId }}</li>
                                <li>סכום כולל: {{ $bankTransfer->paymentMethodTotal }}</li>
                            </ul>
                        @endforeach

                        @foreach ($invoice->payment->paymentMethodCheques as $cheque)
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

                        <h6>סכום כולל של כל התשלומים: {{ $invoice->payment->paymentTotal}}</h6>
                    @endif

                    @if ($invoice->invoiceType->name == 'Invoice' ||
                        $invoice->invoiceType->name == 'InvoiceVAT')
                        @if ($invoice->receipts)
                            <h4>חשבוניות קשורות</h4>
                            <ul>
                                @foreach ($invoice->receipts as $receipt)
                                    <li>
                                        <a href="/businesses/{{$invoice->business->id}}/receipts/{{$receipt->id}}">
                                            חשבונית מספר: {{$receipt->id}} <br>
                                            סכום: {{$receipt->totalPrice}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif

                    @if ($invoice->invoiceType->name == 'Draft' ||
                        $invoice->invoiceType->name == 'DraftVAT')
                            <h4>תפריט ממשקים</h4>
                            <ul>
                                <li>
                                    <a href="/businesses/{{ $invoice->business->id }}/invoices/{{ $invoice->id }}/edit">ערוך מסמך</a>
                                </li>
                                <li>
                                    <form action="/businesses/{{ $invoice->business->id }}/invoices/{{ $invoice->id}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">מחק חשבונית</button>
                                    </form>
                                </li>
                            </ul>    
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection