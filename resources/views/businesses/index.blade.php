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
                    
                    @if (Auth::user()->plan->maxBusinesses > Auth::user()->businesses->count())
                        <ul>  
                            <li>
                                <a href="/businesses/create">צור עסק חדש</a>
                            </li>
                        </ul>      
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">שם העסק</th>
                                <th scope="col">משתמש</th>
                                <th scope="col">עריכה</th>
                                <th scope="col">מחיקה</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($businesses as $business)
                                <tr>
                                    <th scope="row">
                                        <a href="/businesses/{{ $business->id }}">{{ $business->name }}</a>
                                    </th>
                                    <th scope="row">
                                        {{ $business->user_id }}
                                    </th>
                                    <th scope="row">
                                        <a href="/businesses/{{ $business->id }}/edit">ערוך</a>
                                    </th>
                                    <th scope="row">
                                        <form action="/businesses/{{ $business->id }}" method="POST">
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