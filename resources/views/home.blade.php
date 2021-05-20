@extends('layouts.app')

@section('content')
<div class="container-fluid">    
    <div class="row justify-content-right">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card">
                <h3>
                    ברוך הבא, {{Auth::User()->name}}
                </h3>
                <h4>
                    {{$business->name}}
                    @if (Auth::user()->businesses->count()>0)
                        <a href="/businesses">החלף עסק/ערוך עסק</a>
                    @endif
                </h4>
            </div>
            <br>
            <div class="card">
                <div class="card-header" style="background:white;">
                    <h7 style="color:#007bff;">שערי מט"ח נכון לתאריך: {{$currencies['LAST_UPDATE']}}</h7>
                </div>

                <div class="table-responsive">
                    <table class="table" style="font-size:14px; ">
                        <thead>
                            <tr>
                                <th scope="col">שם</th>
                                <th scope="col">שער</th>
                                <th scope="col">שינוי יומי</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currencies['CURRENCY'] as $currency)
                                <tr>
                                    <th scope="row">
                                        {{$currency['CURRENCYCODE']}}
                                    </th>
                                    <th scope="row">
                                        {{$currency['RATE']}}
                                    </th>
                                    <th scope="row">
                                        @if ($currency['CHANGE']>0)
                                            <div style="color:#32CD32">{{$currency['CHANGE']}}</div>    
                                        @endif
                                        @if ($currency['CHANGE']<0)
                                            <div style="color:red">{{$currency['CHANGE']}}</div>    
                                        @endif
                                        @if ($currency['CHANGE']==0)
                                            <div>{{$currency['CHANGE']}}</div>    
                                        @endif
                                        
                                    </th>
                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection