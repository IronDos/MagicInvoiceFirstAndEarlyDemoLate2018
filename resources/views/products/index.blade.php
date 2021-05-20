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
                    
                    <h4>תפריט ממשקים</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{ $business->id }}/products/create">צור מוצר חדש</a>
                        </li>
                    </ul>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">שם המוצר</th>
                                <th scope="col">כמות</th>
                                <th scope="col">מחיר</th>
                                <th scope="col">מחיקה</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <th scope="row">
                                        <a href="/businesses/{{ $business->id }}/products/{{ $product->id }}">{{ $product->name }}</a>
                                    </th>
                                    <th scope="row">
                                        {{ $product->quantity }}
                                    </th>
                                    <th scope="row">
                                        {{ $product->price }}
                                    </th>
                                    <th scope="row">
                                        <form action="/businesses/{{ $business->id }}/products/{{ $product->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">מחק</button>
                                        </form>
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
@endsection