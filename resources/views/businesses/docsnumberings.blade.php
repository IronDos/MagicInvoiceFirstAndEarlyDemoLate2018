@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    
                    <div class="alert alert-info text-center" role="alert">
                        <h6>
                            ניתן להגדיר לספרור מסמכים על הגדרה אישית לסוגי מסמכים אשר טרם הופקו
                        </h6>    
                    </div>
                    <div id="app">
                        <form method="POST"
                            action="/customers"
                            v-on:submit.prevent="onSubmit">
                            @csrf

                            @if ($business->user->plan->bType == 'ExemptDealer')
                                <div class="form-group row">
                                    <label for="Draft"
                                        class="col-md-4 col-form-label text-md-right">טיוטה / הצעת מחיר</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="Draft"
                                            name="Draft"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.Draft }"
                                            required autofocus
                                            min=0
                                            :disabled="disabled.Draft"
                                            v-model="docsTypes.Draft">
                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.Draft"
                                        v-bind:class="{'invalid-feedback': errors.Draft}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Invoice"
                                        class="col-md-4 col-form-label text-md-right">חשבונית עסקה</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="Invoice"
                                            name="Invoice"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.Invoice }"
                                            required autofocus
                                            min=0
                                            v-model="docsTypes.Invoice">

                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.Invoice"
                                        v-bind:class="{'invalid-feedback': errors.Invoice}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="InvoiceReceipt"
                                        class="col-md-4 col-form-label text-md-right">חשבונית קבלה</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="InvoiceReceipt"
                                            name="InvoiceReceipt"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.InvoiceReceipt }"
                                            required autofocus
                                            min=0
                                            v-model="docsTypes.InvoiceReceipt">

                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.InvoiceReceipt"
                                        v-bind:class="{'invalid-feedback': errors.InvoiceReceipt}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if ($business->user->plan->bType == 'AuthorizedDealer')
                                <div class="form-group row">
                                    <label for="DraftVAT"
                                        class="col-md-4 col-form-label text-md-right">טיוטה / הצעת מחיר</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="DraftVAT"
                                            name="DraftVAT"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.DraftVAT }"
                                            required autofocus
                                            min=0
                                            :disabled="disabled.DraftVAT"
                                            v-model="docsTypes.DraftVAT">
                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.DraftVAT"
                                        v-bind:class="{'invalid-feedback': errors.DraftVAT}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="InvoiceVAT"
                                        class="col-md-4 col-form-label text-md-right">חשבונית מס</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="InvoiceVAT"
                                            name="InvoiceVAT"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.InvoiceVAT }"
                                            min="0"
                                            required autofocus
                                            
                                            :disabled="disabled.InvoiceVAT"
                                            v-model="docsTypes.InvoiceVAT">

                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.InvoiceVAT"
                                        v-bind:class="{'invalid-feedback': errors.InvoiceVAT}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="InvoiceVATReceipt"
                                        class="col-md-4 col-form-label text-md-right">חשבונית מס קבלה</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="InvoiceVATReceipt"
                                            name="InvoiceVATReceipt"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.InvoiceVATReceipt }"
                                            required autofocus
                                            min=0
                                            :disabled="disabled.InvoiceVATReceipt"
                                            v-model="docsTypes.InvoiceVATReceipt">

                                        <span
                                        role="alert"
                                        class="invalid-feedback"
                                        v-if="errors.InvoiceVATReceipt"
                                        v-bind:class="{'invalid-feedback': errors.InvoiceVATReceipt}">
                                            <strong>השדה אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="receipt"
                                    class="col-md-4 col-form-label text-md-right">קבלה</label>
                                <div class="col-md-6">
                                    <input type="number"
                                        id="receipt"
                                        name="receipt"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.receipt }"
                                        required autofocus
                                        min=0
                                        :disabled="disabled.Receipt"
                                        v-model="docsTypes.Receipt">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.receipt"
                                    v-bind:class="{'invalid-feedback': errors.receipt}">
                                        <strong>השדה אינו תקין</strong>
                                    </span>
                                </div>
                            </div>
                            

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        עדכן ספרור מסמכים
                                    </button>
                                    @{{msg}}
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
            docsTypes: {!! json_encode($docsTypes) !!},
            draft: '',
            invoice: '',
            receipt: '',
            invoiceReceipt: '',
            disabled: {!! json_encode($disabled) !!},
            errors: '',
            msg:''
        },
        methods: {
            onSubmit() {
                axios.post('/businesses/{!! $business->id !!}/docsnumberings', {
                    Draft: this.docsTypes.Draft,
                    DraftVAT: this.docsTypes.DraftVAT,

                    Invoice: this.docsTypes.Invoice,
                    InvoiceVAT: this.docsTypes.InvoiceVAT,

                    InvoiceReceipt: this.docsTypes.InvoiceReceipt,
                    InvoiceVATReceipt: this.docsTypes.InvoiceVATReceipt,

                    Receipt: this.docsTypes.Receipt,
                }).then(response => {
                    // this.Draft = '',
                    // this.DraftVAT = '',

                    // this.Invoice = '',
                    // this.InvoiceVAT = '',

                    // this.InvoiceReceipt = '',
                    // this.InvoiceVATReceipt = '',

                    // this.Receipt = '',
                    this.msg = "GG",
                    this.errors = ''
                }).catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                });
            }
        }
    });
</script>
@endsection