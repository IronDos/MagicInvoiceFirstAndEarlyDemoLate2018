@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <h4>פרטי המוצר</h4>
                    <ul>
                        <li>שם המוצר: {{$product->name}}</li>
                        <li>כמות: {{$product->quantity}}</li>
                        <li>מחיר: {{$product->price}}</li>                       
                    </ul>
                    <h4>תפריט ממשקים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/products/{{ $product->id }}/edit">ערוך מוצר</a>
                        </li>
                    </ul>

                    @if ($product->productLineInInvoices != null)
                    <h4>מכירות אחרונות</h4>
                        <div class="table-responsive">
                            <table class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th scope="col">תאריך</th>
                                        <th scope="col">כמות</th>
                                        <th scope="col">מחיר ליחידה</th>
                                        <th scope="col">סוג מסמך</th>
                                        <th scope="col">לקוח</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->productLineInInvoices->sortBy('date') as $productLine)
                                        <tr> 
                                            <th scope="row">
                                                <a href="/businesses/{{ $business->id}}/invoices/{{$productLine->invoice->id}}">
                                                    {{$productLine->invoice->date}}
                                                </a>
                                            </th>
                                            <th scope="row">
                                                {{ $productLine->quantity }}
                                            </th>
                                            <th scope="row">
                                                {{$productLine->productPrice}}
                                            </th>
                                            <th scope="row">
                                                <a href="/businesses/{{ $business->id}}/invoices/{{$productLine->invoice->id}}">
                                                    {{$productLine->invoice->invoiceType->title}}
                                                </a>
                                            </th>
                                            <th scope="row">
                                                <a href="/businesses/{{$business->id}}/customers/{{$productLine->invoice->customer->id}}">
                                                    {{ $productLine->invoice->customer->name }}
                                                </a>
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