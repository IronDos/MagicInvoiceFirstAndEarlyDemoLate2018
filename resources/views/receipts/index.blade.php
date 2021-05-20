@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    
                    <h4>תפריט ממשקים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/receipts/create">צור קבלה חדשה</a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col">מס' חשבונית</th>
                                    <th scope="col">תאריך</th>
                                    <th scope="col">לקוח</th>
                                    <th scope="col">מטבע</th>
                                    <th scope="col">סכום</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <th scope="row">
                                            <a href="/businesses/{{ $business->id }}/receipts/{{$receipt->id}}">{{ $receipt->id }}</a>
                                        </th>
                                        <th scope="row">
                                            {{ $receipt->date }}
                                        </th>
                                        <th scope="row">
                                            {{ $receipt->customer->name }}
                                        </th>
                                        <th scope="row">
                                                {{ $receipt->currency->name }}
                                            </th>
                                        <th scope="row">
                                            {{ $receipt->totalPrice }}
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