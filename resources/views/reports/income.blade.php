@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>
                <div class="card-body">
                    <h4>הפק דוח הכנסות</h4>
                    <div id="app">
                        <form method="POST" action="/businesses/{!! $business->id !!}/reports/VAT"
                            v-on:submit.prevent="incomeReport">
                            @csrf
                            
                            <div class="form-group row">
                                <label for="startDate" class="col-md-4 col-form-label text-md-right">מתאריך</label>
                                <div class="col-md-6">
                                    <input type="date"
                                        id="startDate"
                                        name="startDate"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.startDate }"
                                        required autofocus
                                        v-model="startDate">
                            
                                    <span
                                    role="alert"
                                    v-if="errors.startDate"
                                    v-bind:class="{'invalid-feedback': errors.startDate}">
                                        <strong>@{{ errors.startDate }}</strong>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="endDate" class="col-md-4 col-form-label text-md-right">עד תאריך</label>
                                <div class="col-md-6">
                                    <input type="date"
                                        id="endDate"
                                        name="endDate"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.endDate }"
                                        required autofocus
                                        v-model="endDate">
                            
                                    <span
                                    role="alert"
                                    v-if="errors.endDate"
                                    v-bind:class="{'invalid-feedback': errors.endDate}">
                                        <strong>@{{ errors.endDate }}</strong>
                                    </span>
                                </div>
                            </div>

                            @include('layouts.formsinputs.selectcustomeronly')

                            <h5>בחר סוג מסמכים שיכנסו לדוח</h5>
                            <div class="row">
                                <div
                                class="custom-control custom-checkbox my-1 mr-sm-2"
                                v-for="(invoiceType,index) in invoiceTypes">
                                    <input type="checkbox"
                                    v-bind:name="'invoiceType' + invoiceType.id"
                                    v-bind:id="'invoiceType' + invoiceType.id"
                                    class="custom-control-input"
                                    v-model="invoiceType.selected"
                                    v-bind:checked="invoiceType.selected">
                                    <label class="custom-control-label" v-bind:for="'invoiceType' + invoiceType.id">
                                        @{{ invoiceType.title }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                    <input type="checkbox"
                                    id="receiptCheckBox"
                                    class="custom-control-input"
                                    v-model="receiptCheckBox">
                                    <label class="custom-control-label" for="receiptCheckBox">קבלה</label>
                                </div>
                            </div>

                            <div role="alert"
                            v-if="errors.invoiceTypes"
                            class="alert alert-danger" >
                                אחד מסוגי המסמכים אינו חוקי
                            </div>
                            

                            <h5 class="text-center">דוח הכנסות בין התאריכים @{{startDate}} עד @{{endDate}}</h5>
                            <ul>
                                <li>
                                    סה"כ הכנסות לפני מע"מ: @{{totalPriceBeforeVAT}}
                                </li>
                                <li>
                                    סה"כ מע"מ: @{{VAT}}
                                </li>
                                <li>
                                    סה"כ הכנסות: @{{totalPrice}}
                                </li>
                            </ul>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        הפק דוח
                                    </button>
                                </div>
                            </div>
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

            // Customers
            customers: {!! $customers !!},
            selectedCustomer: '',
            
            // InvoiceTypes
            invoiceTypes: {!! json_encode($invoiceTypes) !!},

            totalPrice: '',
            totalPriceBeforeVAT: '',
            VAT: '',

            receiptCheckBox: true,
            errors: '',
        },
        methods: {
            incomeReport() {
                axios.post('/businesses/{!! $business->id !!}/reports/income', {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    selectedCustomer: this.selectedCustomer,
                    invoiceTypes: this.invoiceTypes
                }).then(response => {
                    this.errors = '',
                    this.totalPrice = response.data['totalPrice'],
                    this.totalPriceBeforeVAT = response.data['totalPriceBeforeVAT'],
                    this.VAT = response.data['VAT']
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