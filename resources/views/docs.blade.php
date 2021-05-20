@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
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



                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
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
                                            {{ $doc->customerRecord->name }}
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection