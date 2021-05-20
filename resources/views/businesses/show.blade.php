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
                    
                    <h4>פרטי העסק</h4>
                    <ul>
                        <li>שם העסק: {{$business->name}}</li>
                        <li>מספר עוסק: {{$business->businessTaxIdNumber}}</li>
                        <li>טלפון: {{$business->phone}}</li>
                        <li>פקס: {{$business->fax}}</li>
                        <li>אימייל: {{$business->email}}</li>
                        <li>כתובת: {{$business->address}}</li>
                        <li>עיר: {{$business->city}}</li>
                        <li>אתר אינטרנט: {{$business->website}}</li>
                        <li>תת כותרת: {{$business->subTitle}}</li>
                        <li>סוג מטבע: {{$business->currency->name}}</li>
                        <li>הערות: {{$business->notes}}</li>
                        
                    </ul>
                    <h4>תפריט ממשקים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/customers">מאגר לקוחות</a>
                        </li>
                        <li>
                            <a href="/businesses/{{ $business->id }}/products">מאגר מוצרים</a>
                        </li>
                        <li>
                            <a href="/businesses/{{ $business->id }}/invoices">מאגר חשבוניות</a>
                        </li>
                        <li>
                            <a href="/businesses/{{ $business->id }}/receipts">מאגר קבלות</a>
                        </li>
                    </ul>

                    <h4>הפק מסמכים</h4>
                    @if ($business->user->plan->bType =='AuthorizedDealer')
                        <ul>
                            @foreach ($invoiceTypes as $invoiceType)
                                @if ($invoiceType->bType == 'AuthorizedDealer')
                                    <li>
                                        <a href="/businesses/{{ $business->id }}/invoices/create/{{ $invoiceType->id }}">{{$invoiceType->title}}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                    @if ($business->user->plan->bType =='ExemptDealer')
                        <ul>
                            @foreach ($invoiceTypes as $invoiceType)
                                @if ($invoiceType->bType == 'ExemptDealer')
                                    <li>
                                        <a href="/businesses/{{ $business->id }}/invoices/create/{{ $invoiceType->id }}">{{$invoiceType->title}}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/receipts/create">צור קבלה</a>
                        </li>
                    </ul>
                    <h4>ספרור מסמכים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/docsnumberings">ספרור מסמכים</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection