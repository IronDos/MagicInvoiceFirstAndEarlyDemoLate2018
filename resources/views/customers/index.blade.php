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
                            <a href="/businesses/{{ $business->id }}/customers/create">צור לקוח חדש</a>
                        </li>
                    </ul>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">שם הלקוח</th>
                                <th scope="col">טלפון</th>
                                <th scope="col">אימייל</th>
                                <th scope="col">כתובת</th>
                                <th scope="col">עיר</th>
                                <th scope="col">הערות</th>
                                <th scope="col">מחיקה</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <th scope="row">
                                        <a href="/businesses/{{ $business->id }}/customers/{{ $customer->id }}">{{ $customer->name }}</a>
                                    </th>
                                    <th scope="row">
                                        {{ $customer->phone }}
                                    </th>
                                    <th scope="row">
                                        {{ $customer->email }}
                                    </th>
                                    <th scope="row">
                                        {{ $customer->address }}
                                    </th>
                                    <th scope="row">
                                        {{ $customer->city }}
                                    </th>
                                    <th scope="row">
                                        {{ $customer->notes }}
                                    </th>
                                    <th scope="row">
                                        <form action="/businesses/{{ $business->id }}/customers/{{ $customer->id}}" method="POST">
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