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

                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col">מס' חשבונית</th>
                                    <th scope="col">תאריך</th>
                                    <th scope="col">סוג מסמך</th>
                                    <th scope="col">לקוח</th>
                                    <th scope="col">מטבע</th>
                                    <th scope="col">סכום</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <th scope="row">
                                            <a href="/businesses/{{ $business->id }}/invoices/{{$invoice->id}}">{{ $invoice->id }}</a>
                                        </th>
                                        <th scope="row">
                                            {{ $invoice->date }}
                                        </th>
                                        <th scope="row">
                                            {{ $invoice->invoiceType->title }}
                                        </th>
                                        <th scope="row">
                                            {{ $invoice->customer->name }}
                                        </th>
                                        <th scope="row">
                                                {{ $invoice->currency->name }}
                                            </th>
                                        <th scope="row">
                                            {{ $invoice->totalPrice }}
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection