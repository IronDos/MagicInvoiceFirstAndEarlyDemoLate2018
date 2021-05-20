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
                        <form method="POST" action="/businesses/{!! $business->id !!}/invoices"
                            v-on:submit.prevent="onSubmit">
                            @csrf
    
                            @include('layouts.formsinputs.date')

                            @include('layouts.formsinputs.selectcustomer') 
    
                            @include('layouts.formsinputs.lineininvoice')
    
                            <div class="form-group row">
                                <label for="selectedCurrency" class="col-md-4 col-form-label text-md-right">סוג מטבע</label>
                                <div class="col-md-6">
                                    <select name="selectedCurrency"
                                            id="selectedCurrency"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.selectedCurrency }"
                                            v-on:change="sumInvoiceByCurrency()"
                                            v-model="selectedCurrency">
                                        <option value=""></option>
                                        <option v-for="currency in currencies" v-bind:value="currency.name">@{{ currency.description }}</option>
                                        <option value="עד מתי">עד מתי</option>
                                    </select>
                                    <span
                                    role="alert"
                                    v-if="errors.selectedCurrency"
                                    v-bind:class="{'invalid-feedback': errors.selectedCurrency}">
                                        <strong>סוג המטבע אינו תקין</strong>
                                    </span>
                                </div>
                            </div> 
    
                            <div class="form-group row">
                                <label for="invoiceDiscount"
                                class="col-md-4 col-form-label text-md-right">הנחה למסמך</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select
                                        id="productDiscont"
                                        name="productDiscont"
                                        class="custom-select"
                                        v-on:input="sumInvoice()"
                                        v-model="invoiceDiscountType">
                                            <option value="Percentage">%</option>
                                            <option value="Money">כמות</option>
                                        </select>
                                        <input type="text"
                                        class="form-control"
                                        aria-describedby="basic-addon3"
                                        v-on:input="sumInvoice()"
                                        v-model="invoiceDiscountAmount">
                                    </div>
    
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
                                    step="0.01"
                                    disabled
                                    required autofocus
                                    v-model="invoiceTotalPriceBeforeVAT">
    
                                    <span
                                    role="alert"
                                    v-if="errors.invoiceTotalPrice"
                                    v-bind:class="{'invalid-feedback': errors.invoiceTotalPrice}">
                                        <strong>מחיר לפני מ"עמ לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row" v-if="bType === 'AuthorizedDealer' && mixed == 'false'">
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
    
                            <div class="form-group row" v-if="VATRequired === true">
                                <label for="invoiceTotalPrice"
                                class="col-md-4 col-form-label text-md-right">מחיר סופי לתשלום</label>
                                <div class="col-md-6">
                                    <input type="number"
                                    id="invoiceTotalPrice"
                                    name="invoiceTotalPrice"
                                    class="form-control"
                                    v-bind:class="{ 'is-invalid': errors.invoiceTotalPrice }"
                                    min="0"
                                    step="0.01"
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
                            
                            @if ($invoiceType->name == 'InvoiceReceipt' ||
                                $invoiceType->name == 'InvoiceVATReceipt' ||
                                $invoiceType->name == 'MixedInvoiceReceipt' ||
                                $invoiceType->name == 'MixedInvoiceVATReceipt')
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
<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            bType: '{!! $bType !!}',
            mixed: '{!! $mixed !!}',
            date: '{!! $date !!}',
            minDate: '{!! $date !!}',

            // Customers
            customers: {!! $customers !!},
            selectedCustomer: '',
            customerName: '',
            customerType:'',
            saveCustomer: '',

            availableProducts: {!! $availableProducts !!},
            
            // Currencies
            currencies: {!! $currencies !!},
            selectedCurrency: '{!! $selectedCurrency !!}',
            currencyDate: '{!! $currencyDate !!}',
            
            products: [{
                pType: '',
                pName: "",
                newProductName: '',
                pSave: '',
                VATRequired: false,
                pSelectedCurrency: '{!! $selectedCurrency !!}',
                pQuantity: 1,
                pPrice: 0,
                pTotalPrice: 0,
                pDiscountType: 'Percentage',
                pDiscountAmount: 0,
                pTotalPriceAfterDiscount: 0,
                pTotalPriceRow: 0,
                pTotalPriceRowAndVAT: 0,
                //pTotalPriceRowAfterVAT = רק תגית
            }],
            invoiceDiscount: 0,
            invoiceTotalPriceBeforeVAT: 0,
            InvoiceTotalPriceBeforeVATLabel: 'מחיר לפני מע"מ',
            VAT: '{!! $VAT !!}'*100,
            VATRequired: true,
            invoiceTotalPrice: 0,
            invoiceDiscountType: 'Percentage',
            invoiceDiscountAmount: 0,
            notes:'{!! $notes !!}',
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
            paymentsTotal: '',
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
            },
            
            getPriceAfterDiscount(price, discountType, discountAmount)
            {
                if (discountType == 'Percentage') {
                    return Number((price - ((discountAmount/100)*price)).toFixed(2));
                }
                
                if (discountType == 'Money') {
                    return Number((price - discountAmount).toFixed(2));
                }
            },

            sumInvoiceByCurrency() {
                counter = 0;
                this.products.forEach(product => {
                    this.sumProductByCurrency(counter);
                    counter++;
                });
                this.sumInvoice();
            },

            sumInvoice() {
                this.invoiceTotalPriceBeforeVAT = 0;
                this.invoiceTotalPrice = 0;

                this.products.forEach(element => {
                    element['pTotalPriceRow'] = Number(element['pTotalPriceRow'].toFixed(2));
                    this.invoiceTotalPriceBeforeVAT += Number(element['pTotalPriceRow'].toFixed(2));
                    if (element['pVATRequired'] == true) {
                        this.invoiceTotalPrice += Number(element['pTotalPriceRow'].toFixed(2)) + Number(element['pTotalPriceRow'].toFixed(2)) * Number((this.VAT/100).toFixed(2));
                    } else {
                        this.invoiceTotalPrice += Number(element['pTotalPriceRow']);
                    }
                });

                this.invoiceTotalPriceBeforeVAT = this.getPriceAfterDiscount(
                    this.invoiceTotalPriceBeforeVAT,
                    this.invoiceDiscountType,
                    this.invoiceDiscountAmount);
                this.invoiceTotalPrice = this.getPriceAfterDiscount(
                    this.invoiceTotalPrice,
                    this.invoiceDiscountType,
                    this.invoiceDiscountAmount);
            },

            // Start Products Methods
            getPrice(pName,index) {
                this.availableProducts.forEach(element => {
                    if (element['id'] == pName) {
                        this.products[index]['pPrice'] = element['price'];
                        this.setProductCurrency(index, element['currency_id']);
                        if (element['VATRequired'] == 'Yes') {
                            this.products[index]['pVATRequired'] = true;
                        } else { this.products[index]['pVATRequired'] = false;}
                    }
                });
                this.sumProduct(index, 'price');
                this.sumInvoiceByCurrency();
            },
            
            setProductCurrency(index, currencyId) {
                currencyName = '';
                this.currencies.forEach(currency => {
                    if (currency.id == currencyId) { currencyName = currency.name; }
                });
                this.products[index]['pSelectedCurrency'] = currencyName;
            },

            sumProduct(index, from) {
                if (from == 'price') {
                    this.products[index].pTotalPrice = this.products[index].pQuantity * this.products[index].pPrice;
                    this.products[index].pTotalPriceAfterDiscount = this.getPriceAfterDiscount(
                        this.products[index].pTotalPrice,
                        this.products[index].pDiscountType,
                        this.products[index].pDiscountAmount
                    );
                }

                if (from == 'totalPrice') {
                    this.products[index].pPrice =  this.products[index].pTotalPrice / this.products[index].pQuantity;
                    this.products[index].pTotalPriceAfterDiscount = this.getPriceAfterDiscount(
                        this.products[index].pTotalPrice,
                        this.products[index].pDiscountType,
                        this.products[index].pDiscountAmount
                    );
                }
                this.sumProductByCurrency(index);
                this.sumInvoiceByCurrency();
            },

            sumProductByCurrency(index) {
                if (this.products[index].pSelectedCurrency != this.selectedCurrency) {
                    this.currencies.forEach(currency => {
                        if (this.products[index].pSelectedCurrency == currency.name) {
                            productCurrencyRate = Number(currency.rate).toFixed(2);
                            productCurrencyUnit = Number(currency.unit).toFixed(2);
                        }

                        if (this.selectedCurrency == currency.name) {
                            invoiceCurrencyRate = Number(currency.rate).toFixed(2);
                            invoiceCurrencyUnit = Number(currency.unit).toFixed(2);
                        }
                    });
                    // this.products[index].pTotalPriceRow = (this.products[index].pTotalPriceAfterDiscount * productCurrencyRate * productCurrencyUnit) *
                    // (invoiceCurrencyRate * invoiceCurrencyUnit);
                    this.products[index].pTotalPriceRow = (productCurrencyRate / invoiceCurrencyRate) * this.products[index].pTotalPriceAfterDiscount;
                    this.products[index].pTotalPriceRow = this.products[index].pTotalPriceRow / (productCurrencyUnit / invoiceCurrencyUnit) ;

                } else {
                    this.products[index].pTotalPriceRow = this.products[index].pTotalPriceAfterDiscount;
                }

                this.products[index].pTotalPriceRowAndVAT = Number((this.products[index].pTotalPriceRow + this.products[index].pTotalPriceRow * (this.VAT/100)).toFixed(2));
            },

            addproduct() {
                var elem = document.createElement('tr');
                this.products.push({
                    pType: '',
                    pName: "",
                    newProductName: '',
                    pSave: '',
                    VATRequired: false,
                    pQuantity: "1",
                    pPrice: 0,
                    pTotalPrice: 0,
                    pDiscountType: 'Percentage',
                    pDiscountAmount: 0,
                    pTotalPriceAfterDiscount: 0
                });
            },
            removeLineInInvoice(index) {
                this.products.splice(index, 1);
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
                    paymentTotal: ""
                });
            },
            RemovePayment(index) {
                this.payments.splice(index, 1);
            },
            SumPaymentsTotal(){
                this.paymentsTotal = 0;
                this.payments.forEach(element => {
                    this.paymentsTotal += Number(element['paymentTotal']);
                });
            },

            // End Payments Function

            onSubmit() {
                axios.post('/businesses/{!! $business->id !!}/invoices/{!! $invoiceType->id !!}', {
                    date: this.date,

                    // Start Customer
                    selectedCustomer: this.selectedCustomer,
                    customerName: this.customerName,
                    customerType: this.customerType,
                    saveCustomer: this.saveCustomer,
                    // End Customer

                    currencyDate: this.currencyDate,
                    
                    // Start Products
                    products: this.products,
                    // End Products

                    selectedCurrency: this.selectedCurrency,
                    invoiceDiscountType: this.invoiceDiscountType,
                    invoiceDiscountAmount: this.invoiceDiscountAmount,
                    invoiceTotalPriceBeforeVAT: this.invoiceTotalPriceBeforeVAT,
                    VAT: this.VAT,
                    invoiceTotalPrice: this.invoiceTotalPrice,
                    notes: this.notes,
                    // Start Payment
                    payments: this.payments,
                    paymentsTotal: this.paymentsTotal,
                    // End Payment

                    VATRequired: this.VATRequired

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
                        VATRequired: false,
                        pQuantity: "1",
                        pPrice: "",
                        pTotalPrice: "",
                        pDiscountType: 'Percentage',
                        pDiscountAmount: 0,
                        pTotalPriceAfterDiscount: 0
                    }],
                    this.selectedCurrency = '',
                    this.invoiceDiscount = '',
                    this.invoiceTotalPriceBeforeVAT = '',
                    this.VAT = '{!! $VAT !!}'*100,
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
                    
                    this.paymentsTotal = '',
                    // // End Payment Rest

                    this.VATRequired = '',

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
                    this.newProductNameErrors =''

                    this.invoiceTotalPriceBeforeVATError = '',
                    this.invoiceTotalPriceError = ''
                    // End Errors Reset

                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                        if (this.errors['products']) {
                            this.pTypeErrors = this.errors['products'][0]['productTypeErrors'],
                            this.pNameErrors = this.errors['products'][0]['productNameErrors'],
                            this.pQuantityErrors = this.errors['products'][0]['productQuantityErrors'],
                            this.pPriceErrors = this.errors['products'][0]['productPriceErrors'],
                            this.pTotalPriceErrors = this.errors['products'][0]['productTotalPriceErrors'],
                            this.newProductNameErrors = this.errors['products'][0]['newProductNameErrors'],
                            this.productsDiscountsErrors = this.errors['products'][0]['DiscountErrors']
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