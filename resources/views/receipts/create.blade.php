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

                    <div id="app">
                        <form method="POST" action="/businesses/{!! $business->id !!}/receipts"
                            v-on:submit.prevent="onSubmit">
                            @csrf
                            
                            @include('layouts.formsinputs.date')
    
                            @include('layouts.formsinputs.selectcustomer')

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">תיאור</label>
                                <div class="col-md-6">
                                    <textarea name="description"
                                        id="description"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.description }"
                                        cols="30" rows="2"
                                        placeholder='תיאור המסמך(אינו חובה)'
                                        v-model="description"></textarea>
                            
                                    <span
                                    role="alert"
                                    v-if="errors.description"
                                    class="invalid-feedback">
                                        <strong>התיאור אינו תקינות</strong>
                                    </span>
                                </div>
                            </div>
    
                            @include('layouts.formsinputs.selectcurrency')
                            
                            <div class="form-group row">
                                <label for="invoiceTotalPrice"
                                class="col-md-4 col-form-label text-md-right">מחיר סופי ששולם</label>
                                <div class="col-md-6">
                                    <input type="number"
                                    id="invoiceTotalPrice"
                                    name="invoiceTotalPrice"
                                    class="form-control"
                                    v-bind:class="{ 'is-invalid': errors.invoiceTotalPrice }"
                                    min="0"
                                    required autofocus
                                    v-model="invoiceTotalPrice">
    
                                    <span
                                    role="alert"
                                    v-if="errors.invoiceTotalPrice"
                                    v-bind:class="{'invalid-feedback': errors.invoiceTotalPrice}">
                                        <strong>מחיר סופי אינו תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            @include('layouts.formsinputs.notes')

                            <div class="form-group row">
                                <label for="selectedInvoice" class="col-md-4 col-form-label text-md-right">בחר שיוך לחשבונית</label>
                                <div class="col-md-6">
                                    <select name="selectedInvoice"
                                            id="selectedInvoice"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.selectedInvoice }"
                                            v-model="selectedInvoice">
                                        <option value=""></option>
                                        <option v-for="invoice in invoices" v-bind:value="invoice.id">
                                            חשבונית מס':@{{ invoice.id }}
                                            לקוח:@{{ invoice.customerName }}
                                            סכום:@{{ invoice.totalPrice }}
                                        </option>
                                        <option value="Other">אחר</option>
                                    </select>
                                    <span
                                    role="alert"
                                    v-if="errors.selectedInvoice"
                                    v-bind:class="{'invalid-feedback': errors.selectedInvoice}">
                                    <strong>@{{errors.selectedInvoice[0]}}</strong>
                                    </span>
                                </div>
                            </div>

                            @include('layouts.formsinputs.payments')
    
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        צור קבלה
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
        el: "#app",
        data: {
            date: '{!! $date !!}',
            minDate: '{!! $date !!}',

            // Customers
            customers: {!! $customers !!},
            selectedCustomer: '',
            customerName: '',
            customerType:'',
            saveCustomer: '',

            // Currencies
            currencies: {!! $currencies !!},
            selectedCurrency: '{!! $selectedCurrency !!}',

            invoiceTotalPrice: 0,
            description: '',
            notes:'{!! $notes !!}',
            errors: [],

            //Invoices
            invoices: {!! json_encode($invoices) !!},
            selectedInvoice: '',

            //Payments
            payments: [{
                selectMethod: '',
                date: '',
                creditCardLastFourNumbers: '',
                selectCreditCardType: '',
                creditCardTransactionType: '',
                installmentNumber: '',
                bankId: '',
                bankBranchId: '',
                bankAccountId: '',
                chequeId: '',
                paymentTotal: 0
            }],
            paymentsTotal: 0,
            errors: '',
            selectMethodErrors: '',
            dateErrors: '',
            creditCardLastFourNumbersErrors: '',
            selectCreditCardTypeErrors: '',
            creditCardTransactionTypeErrors: '',
            installmentNumberErrors: '',
            bankIdErrors: '',
            bankBranchIdErrors: '',
            bankAccountIdErrors: '',
            chequeIdErrors: '',
            paymentTotalErrors: '',
            paymentsTotalErrors: '',

            invoiceTotalPriceError: ''
        },
        methods: {
            // Start PaymentsFunction
            AddPayment() {
                // var elem = document.createElement('div');
                this.payments.push({
                    selectMethod: '',
                    date: '',
                    creditCardLastFourNumbers: '',
                    selectCreditCardType: '',
                    creditCardTransactionType: '',
                    installmentNumber: '',
                    bankId: '',
                    bankBranchId: '',
                    bankAccountId: '',
                    chequeId: '',
                    paymentTotal: 0
                });
            },
            RemovePayment(index) {
                this.payments.splice(index, 1);
            },
            SumPaymentsTotal(){
                this.paymentsTotal = 0;
                this.payments.forEach(payment => {
                    this.paymentsTotal += Number(payment.paymentTotal);
                });
            },

            // End Payments Function

            onSubmit() {
                axios.post('/businesses/{!! $business->id !!}/receipts', {
                    date: this.date,
                    
                    // Start Customer
                    selectedCustomer: this.selectedCustomer,
                    customerName: this.customerName,
                    customerType: this.customerType,
                    saveCustomer: this.saveCustomer,
                    // End Customer

                    description: this.description,
                    selectedCurrency: this.selectedCurrency,
                    invoiceTotalPrice: this.invoiceTotalPrice,
                    notes: this.notes,
                    selectedInvoice: this.selectedInvoice,
                    // Start Payment
                    payments: this.payments,
                    paymentsTotal: this.paymentsTotal
                    // End Payment

                }).then(response => {
                    this.date = '',
                    
                    this.selectedCustomer = '',
                    this.customerName = '',
                    this.customerType = '',
                    this.saveCustomer = '',

                    this.selectedCurrency = '',
                    this.invoiceTotalPrice = '',
                    this.notes = '',

                    // Start Payment Rest
                    this.payments = [{
                        selectMethod: '',
                        date: '',
                        creditCardLastFourNumbers: '',
                        selectCreditCardType: '',
                        creditCardTransactionType: '',
                        installmentNumber: '',
                        bankId: '',
                        bankBranchId: '',
                        bankAccountId: '',
                        chequeId: '',
                        paymentTotal: 0
                    }],
                    
                    // this.paymentsTotal = '',
                    // // End Payment Rest

                    // Start Errors Reset
                    this.errors = '',
                    this.selectMethodErrors = '',
                    this.dateErrors = '',
                    this.creditCardLastFourNumbersErrors = '',
                    this.selectCreditCardTypeErrors = '',
                    this.creditCardTransactionTypeErrors = '',
                    this.installmentNumberErrors = '',
                    this.bankIdErrors = '',
                    this.bankBranchIdErrors = '',
                    this.bankAccountIdErrors = '',
                    this.chequeIdErrors = '',
                    this.paymentTotalErrors = '',
                    this.paymentsTotalErrors = '',

                    this.invoiceTotalPriceError = ''
                    // Start Errors Reset

                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                        if (this.errors.invoiceTotalPrice) {
                            this.invoiceTotalPriceBeforeVATError = this.errors['invoiceTotalPrice'][0]['invoiceTotalPriceBeforeVAT'],
                            this.invoiceTotalPriceError = this.errors['invoiceTotalPrice'][0]['invoiceTotalPrice']
                        }

                        if (this.errors.payments) {
                            this.selectMethodErrors = this.errors.payments[0][0],
                            this.dateErrors = this.errors.payments[0][1],
                            this.creditCardLastFourNumbersErrors = this.errors.payments[0][2],
                            this.selectCreditCardTypeErrors = this.errors.payments[0][3],
                            this.creditCardTransactionTypeErrors = this.errors.payments[0][4],
                            this.installmentNumberErrors = this.errors.payments[0][5],
                            this.bankIdErrors = this.errors.payments[0][6],
                            this.bankBranchIdErrors = this.errors.payments[0][7],
                            this.bankAccountIdErrors = this.errors.payments[0][8],
                            this.chequeIdErrors = this.errors.payments[0][9],
                            this.paymentTotalErrors = this.errors.payments[0][10],
                            this.paymentsTotalErrors = this.errors.payments[0][11]
                        }
                    }
                })
            },
        }
    });
</script>
@endsection