@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>
                <div class="card-body">
                    <h4>הפק דוח מעמ לפי תקופה</h4>
                    <div id="app">
                        <form method="POST" action="/businesses/{!! $business->id !!}/reports/VAT"
                            v-on:submit.prevent="onSubmit">
                            @csrf
                            
                            @include('layouts.formsinputs.startenddate')

                            <h5 class="text-center">תשלום מעמ בין התאריכים @{{startDate}} עד @{{endDate}}</h5>
                            <ul>
                                <li>
                                    סה"כ עסקאות החייבות מע"מ (ללא אחוז המע"מ): @{{totalPriceBeforeVAT}}
                                </li>
                                <li>
                                    סה"כ עסקאות הפטורות ממע"מ: @{{totalPriceWithoutVAT}}
                                </li>
                                <li>
                                    שיעור המע"מ: @{{VAT}}
                                </li>
                            </ul>
                        </form>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            startDate: '{!! $startDate !!}',
            endDate: '{!! $endDate !!}',
            minDate: '',
            totalPriceBeforeVAT: "{!! $VATReport['totalPriceBeforeVAT'] !!}",
            VAT: "{!! $VATReport['VAT'] !!}",
            totalPriceWithoutVAT: "{!! $VATReport['totalPriceWithoutVAT'] !!}",
            errors: ''
        },
        methods: {
            VATReport() {
                axios.post('/businesses/{!! $business->id !!}/reports/VAT', {
                    startDate: this.startDate,
                    endDate: this.endDate,
                }).then(response => {
                    this.errors = '',
                    this.totalPriceBeforeVAT = response.data['totalPriceBeforeVAT'],
                    this.VAT = response.data['VAT'],
                    this.totalPriceWithoutVAT = response.data['totalPriceWithoutVAT']
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
        }
    });
</script>
@endsection