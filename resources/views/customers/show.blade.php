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

                    <h4>פרטי הלקוח</h4>
                    <ul>
                        <li>שם הלקוח: {{$customer->name}}</li>
                        <li>טלפון: {{$customer->phone}}</li>
                        <li>אימייל: {{$customer->email}}</li>
                        <li>כתובת: {{$customer->address}}</li>
                        <li>עיר: {{$customer->city}}</li>
                        <li>הערות: {{$customer->notes}}</li>                        
                    </ul>
                    <h4>תפריט ממשקים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/customers/{{ $customer->id }}/edit">ערוך לקוח</a>
                        </li>
                    </ul>

                    @if ($docs)
                        <div class="table-responsive">
                            <table class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th scope="col">תאריך</th>
                                        <th scope="col">לקוח</th>
                                        <th scope="col">סוג מסמך</th>
                                        <th scope="col">סטטוס</th>
                                        <th scope="col">סכום</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($docs as $doc)
                                        <tr>
                                            
                                            <th scope="row">
                                                @if (isset($doc->invoiceType))
                                                    <a href="/businesses/{{ $business->id}}/invoices/{{$doc->id}}">
                                                @else
                                                    <a href="/businesses/{{ $business->id}}/receipts/{{$doc->id}}">
                                                @endif
                                                    {{$doc->date}}
                                                    </a>
                                            </th>
                                            <th scope="row">
                                                {{ $doc->customer->name }}
                                            </th>
                                            <th scope="row">
                                                @if (isset($doc->invoiceType))
                                                    {{$doc->invoiceType->title}}
                                                @else
                                                    קבלה
                                                @endif
                                            </th>
                                            <th scope="row">
                                                @if (isset($doc->invoiceStatus))
                                                    {{$doc->invoiceStatus->title}}
                                                @else
                                                    קבלה
                                                @endif
                                            </th>
                                            <th scope="row">
                                                {{ $doc->totalPrice }}
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection