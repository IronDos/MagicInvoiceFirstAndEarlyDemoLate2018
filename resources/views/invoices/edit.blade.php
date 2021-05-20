@extends('layouts.app')

@section('content')

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <div id="app">
                        <form method="POST" action="/businesses/{!! $invoice->business->id !!}/invoices"
                            v-on:submit.prevent="onSubmit">
                            @csrf
    
                            @include('layouts.formsinputs.date')

                            @include('layouts.formsinputs.selectcustomer')
    
                            @include('layouts.formsinputs.lineininvoice')
    
                            @include('layouts.formsinputs.selectcurrency')
    
                            <div class="form-group row">
                                <label for="invoiceDiscount"
                                class="col-md-4 col-form-label text-md-right">הנחה ב-%</label>
                                <div class="col-md-6">
                                    <input type="number"
                                    id="invoiceDiscount"
                                    name="invoiceDiscount"
                                    class="form-control"
                                    v-bind:class="{ 'is-invalid': errors.invoiceDiscount }"
                                    min="0"
                                    max="100"
                                    autofocus
                                    v-model="invoiceDiscount"
                                    v-on:input="sumInvoice()">
    
                                    <span
                                    role="alert"
                                    v-if="errors.invoiceDiscount"
                                    v-bind:class="{'invalid-feedback': errors.invoiceDiscount}">
                                        <strong>הנחה אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row" v-if="bType === 'AuthorizedDealer'">
                                    <label for="invoiceTotalPriceBeforeVAT"
                                    class="col-md-4 col-form-label text-md-right">@{{InvoiceTotalPriceBeforeVATLabel}}</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                        id="invoiceTotalPriceBeforeVAT"
                                        name="invoiceTotalPriceBeforeVAT"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.invoiceTotalPrice }"
                                        min="0"
                                        disabled
                                        required autofocus
                                        v-model="invoiceTotalPriceBeforeVAT">
        
                                        <span
                                        role="alert"
                                        v-if="errors.invoiceTotalPrice"
                                        v-bind:class="{'invalid-feedback': errors.invoiceTotalPrice}">
                                            <strong>מחיר לא תקין</strong>
                                        </span>
                                    </div>
                                </div>
        
                                <div class="form-group row" v-if="bType === 'AuthorizedDealer'">
                                    <label for="VATRequired" class="col-md-4 col-form-label text-md-right">מע"מ</label>
                                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                        <input type="checkbox"
                                        id="VATRequired"
                                        class="custom-control-input"
                                        @change="updateLabelVAT()"
                                        v-model="VATRequired">
                                        <label class="custom-control-label" for="VATRequired">סמן אם ברצונך שהקבלה תכלול מע"מ</label>
                                    </div>
                                </div>
                                
                                <div class="form-group row" v-if="VATRequired == true">
                                    <label for="invoiceTotalPrice"
                                    class="col-md-4 col-form-label text-md-right">מחיר סופי לתשלום</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                        id="invoiceTotalPrice"
                                        name="invoiceTotalPrice"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.invoiceTotalPrice }"
                                        min="0"
                                        readonly
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
                            
                            @if ($invoice->invoiceType->name == 'InvoiceReceipt' || $invoice->invoiceType->name == 'InvoiceVATReceipt')
                                @include('layouts.formsinputs.payments')
                            @endif
                            
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ $title }}
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

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            bType: '{!! $invoice->invoiceType->bType !!}',
            date: '{!! $invoice->date !!}',
            minDate: '{!! $date !!}',
            
            // Customer
            customers: {!! $customers !!},
            selectedCustomer: '{!! $invoice->customer->id !!}',
            customerName: '',
            customerType: 'ExistingCustomer',
            saveCustomer: '',
            // End Customer

            availableProducts: {!! $availableProducts !!},
            currencies: {!! $currencies !!},
            selectedCurrency: '{!! $invoice->currency->name !!}',
            products: {!! json_encode($products) !!},
            invoiceDiscount: '{!! $invoice->discount !!}'*100,
            invoiceTotalPriceBeforeVAT: '{!! $invoice->totalPriceBeforeVAT !!}',
            InvoiceTotalPriceBeforeVATLabel: '{!! $InvoiceTotalPriceBeforeVATLabel !!}',
            VAT: '{!! $VAT !!}' * 100,
            VATRequired: '{!! $VATRequired !!}',
            invoiceTotalPrice: '{!! $invoice->totalPrice !!}',
            notes:'{!! $invoice->notes !!}',
            errors: [],

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

            pTypeErrors: '',
            pNameErrors: '',
            pQuantityErrors: '',
            pPriceErrors: '',
            pTotalPriceErrors: '',
            newProductNameErrors: '',

            invoiceTotalPriceBeforeVATError: '',
            invoiceTotalPriceError: ''
        },
        methods: {
            updateLabelVAT() {
                if (this.VATRequired == false) {
                    this.InvoiceTotalPriceBeforeVATLabel = 'מחיר סופי לתשלום';
                }
                if (this.VATRequired == true) {
                    this.InvoiceTotalPriceBeforeVATLabel = 'מחיר לפני מע"מ';
                }
                this.sumInvoice();
            },

            sumInvoice() {
                sum = 0;
                this.products.forEach(element => {
                    sum+= Number(element['pTotalPrice']);
                });
                this.invoiceTotalPriceBeforeVAT = sum - (this.invoiceDiscount/100 * sum);
                this.invoiceTotalPrice = (this.invoiceTotalPriceBeforeVAT * (this.VAT/100)) + this.invoiceTotalPriceBeforeVAT;
                
            },

            // Start Products Methods
            getPrice(pName,index) {
                this.availableProducts.forEach(element => {
                    if (element['id'] == pName) {
                        this.products[index]['pPrice'] = element['price'];
                    }
                });
                this.sumproduct(index);
            },
            addproduct() {
                var elem = document.createElement('tr');
                this.products.push({
                    pType: '',
                    pName: "",
                    newProductName: '',
                    pSave: '',
                    pQuantity: "1",
                    pPrice: "",
                    pTotalPrice: ""
                });
            },
            removeLineInInvoice(index) {
                this.products.splice(index, 1);
                this.sumInvoice();
            },
            sumproduct(index) {
                this.products[index].pTotalPrice = this.products[index].pQuantity * this.products[index].pPrice;
                this.sumInvoice();
            },
            // End Products Methods

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
                    this.paymentsTotal += payment.paymentTotal;
                });
            },

            // End Payments Function

            onSubmit() {
                axios.put('/businesses/{!! $invoice->business->id !!}/invoices/{!! $invoice->id !!}', {
                    date: this.date,
                    
                    // Start Customer
                    selectedCustomer: this.selectedCustomer,
                    customerName: this.customerName,
                    customerType: this.customerType,
                    saveCustomer: this.saveCustomer,
                    // End Customer

                    // Start Products
                    products: this.products,
                    // End Products
                    selectedCurrency: this.selectedCurrency,
                    invoiceDiscount: this.invoiceDiscount,
                    invoiceTotalPriceBeforeVAT: this.invoiceTotalPriceBeforeVAT,
                    VAT: this.VAT,
                    invoiceTotalPrice: this.invoiceTotalPrice,
                    notes: this.notes,
                    // Start Payment
                    payments: this.payments,
                    paymentsTotal: this.paymentsTotal,
                    // End Payment

                    VATRequired: this.VATRequired,

                }).then(response => {
                    this.date = '{!! $date !!}',
                    this.selectedCustomer = '',
                    this.customerName = '',
                    this.customerType = '',
                    this.saveCustomer = '',

                    this.products = [{
                        pType: '',
                        pName: "",
                        newProductName: '',
                        pSave: '',
                        pQuantity: "1",
                        pPrice: "",
                        pTotalPrice: ""
                    }],
                    this.selectedCurrency = '',
                    this.invoiceDiscount = '',
                    this.invoiceTotalPriceBeforeVAT = '',
                    this.VAT = '{!! $invoice->VAT !!}',
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

                    this.VATRequired = ''
                    
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

                    // Product Errors Reset
                    this.pTypeErrors = '',
                    this.pNameErrors = '',
                    this.pQuantityErrors = '',
                    this.pPriceErrors = '',
                    this.pTotalPriceErrors = '',
                    this.newProductNameErrors = '',

                    this.invoiceTotalPriceBeforeVATError = '',
                    this.invoiceTotalPriceError = ''
                    // Start Errors Reset

                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                        if (this.errors['products']) {
                            this.pTypeErrors = this.errors['products'][0]['productTypeErrors'],
                            this.pNameErrors = this.errors['products'][0]['productNameErrors'],
                            this.pQuantityErrors = this.errors['products'][0]['productQuantityErrors'],
                            this.pPriceErrors = this.errors['products'][0]['productPriceErrors'],
                            this.pTotalPriceErrors = this.errors['products'][0]['productTotalPriceErrors'],
                            this.newProductNameErrors = this.errors['products'][0]['newProductNameErrors']
                        }
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