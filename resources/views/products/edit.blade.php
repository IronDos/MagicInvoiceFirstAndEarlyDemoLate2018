@extends('layouts.app')

@section('content')
{{$VATRequired}}
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
                        <form method="POST"
                            action="/customers"
                            v-on:submit.prevent="onSubmit">
                            @method('PUT')
                            @csrf

                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">שם המוצר</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.name }"
                                        required autofocus
                                        v-model="name">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.name"
                                    v-bind:class="{'invalid-feedback': errors.name}">
                                        <strong>שם המוצר אינו תקין</strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="quantity"
                                    class="col-md-4 col-form-label text-md-right">כמות</label>
                                <div class="col-md-6">
                                    <input type="number"
                                        id="quantity"
                                        name="quantity"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.quantity }"
                                        autofocus
                                        min="0"
                                        v-model="quantity">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.quantity"
                                    v-bind:class="{'invalid-feedback': errors.quantity}">
                                        <strong>הכמות לא תקינה</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price"
                                    class="col-md-4 col-form-label text-md-right">מחיר</label>
                                <div class="col-md-6">
                                    <input type="number"
                                        id="price"
                                        name="price"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.price }"
                                        autofocus
                                        min="0"
                                        step="any"
                                        v-on:input="updateProductPrice(price)"
                                        v-model="price">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.price"
                                    v-bind:class="{'invalid-feedback': errors.price}">
                                        <strong>המחיר לא תקין</strong>
                                    </span>
                                </div>
                            </div>

                            @if ($business->user->plan->bType == 'AuthorizedDealer')
                                <div class="form-group row">
                                    <label for="priceAfterVAT"
                                        class="col-md-4 col-form-label text-md-right">מחיר לאחר מע"מ</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="priceAfterVAT"
                                            name="priceAfterVAT"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.priceAfterVAT }"
                                            autofocus
                                            min="0"
                                            step="any"
                                            v-on:input="updateProductPriceAfterVAT(priceAfterVAT)"
                                            v-model="priceAfterVAT">
    
                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.priceAfterVAT"
                                        v-bind:class="{'invalid-feedback': errors.priceAfterVAT}">
                                            <strong>המחיר לאחר לא תקין</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="VATRequired" class="col-md-4 col-form-label text-md-right">מוצר חייב במע"מ</label>
                                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                        <input type="checkbox"
                                        id="VATRequired"
                                        class="custom-control-input"
                                        v-model="VATRequired">
                                        <label class="custom-control-label" for="VATRequired">סמן אם המוצר מצריך תשלום מע"מ</label>
                                    </div>
                                </div> 
                            @endif

                            @include('layouts.formsinputs.selectcurrency')

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        עדכן מוצר
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
var app = new Vue ({
    el: "#app",
    data: {
        name: '{!! $name !!}',
        quantity: '{!! $quantity !!}',
        price: '{!! $price !!}',
        VATRequired: '{!! $VATRequired !!}',
        priceAfterVAT: '{!! $price !!}' * {!! $VAT !!},

        // Currencies
        currencies: {!! $currencies !!},
        selectedCurrency: '{!! $selectedCurrency !!}',
        errors: ''
    },
    methods: {
        updateProductPrice(price) {
            this.price = price;
            this.priceAfterVAT = price * {!! $VAT !!};
        },

        updateProductPriceAfterVAT(priceAfterVAT) {
            this.priceAfterVAT = priceAfterVAT;
            this.price = priceAfterVAT / {!! $VAT !!};
        },

        onSubmit() {
            axios.put('/businesses/{!! $business->id !!}/products/{!! $product->id !!}', {
                name: this.name,
                quantity: this.quantity,
                price: this.price,
                priceAfterVAT: this.priceAfterVAT,
                VATRequired: this.VATRequired,
                selectedCurrency: this.selectedCurrency
            }).then(response => {
                this.name = '',
                this.quantity = '',
                this.price = '',
                this.priceAfterVAT = '',
                this.VATRequired = false,
                this.selectedCurrency = '{!! $selectedCurrency !!}',
                this.errors = ''
            }).catch(error => {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
            })
        }
    }
});
</script>
@endsection