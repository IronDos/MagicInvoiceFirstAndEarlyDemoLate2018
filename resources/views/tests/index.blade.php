@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                            <a href="/tests/create">צור בדיקה חדשה</a>
                        </li>
                        <li>VAT: {{ $vat }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection