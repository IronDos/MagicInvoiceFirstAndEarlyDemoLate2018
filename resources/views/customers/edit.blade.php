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
                        <form method="POST"
                            action="/customers"
                            v-on:submit.prevent="onSubmit">
                            @method('PUT')
                            @csrf
                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">שם הלקוח</label>
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
                                        <strong>שם הלקוח אינו תקין</strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-right">טלפון</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="phone"
                                        name="phone"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.phone }"
                                        autofocus
                                        v-model="phone">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.phone"
                                    v-bind:class="{'invalid-feedback': errors.phone}">
                                        <strong>טלפון לא תקין</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">אימייל</label>
                                <div class="col-md-6">
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.email }"
                                        autofocus
                                        v-model="email">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.email"
                                    v-bind:class="{'invalid-feedback': errors.email}">
                                        <strong>אימייל לא תקין</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-right">כתובת</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="address"
                                        name="address"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.address }"
                                        autofocus
                                        v-model="address">

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.address"
                                    v-bind:class="{'invalid-feedback': errors.address}">
                                        <strong>כתובת אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="city" class="col-md-4 col-form-label text-md-right">עיר / מושב</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="city"
                                        name="city"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.city }"
                                        autofocus
                                        v-model="city">

                                    <span
                                    role="alert"
                                    v-if="errors.city"
                                    v-bind:class="{'invalid-feedback': errors.city}">
                                        <strong>העיר אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>

                            @if ($business->user->plan->bType == 'AuthorizedDealer')
                                <div class="form-group row">
                                    <label for="VATRequired" class="col-md-4 col-form-label text-md-right">חייב במע"מ</label>
                                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                        <input type="checkbox"
                                        id="VATRequired"
                                        class="custom-control-input"
                                        v-model="VATRequired">
                                        <label class="custom-control-label" for="VATRequired">לקוח זה יהיה מחויב תמיד במע"מ</label>
                                    </div>
    
                                </div> 
                            @endif

                            <div class="form-group row">
                                <label for="notes"
                                    class="col-md-4 col-form-label text-md-right">הערות</label>
                                <div class="col-md-6">
                                    <textarea name="notes"
                                        id="notes"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.notes }"
                                        cols="30" rows="2"
                                        v-model="notes"></textarea>

                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.notes"
                                    v-bind:class="{'invalid-feedback': errors.notes}">
                                        <strong>הערות אינן תקינות</strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        ערוך לקוח
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
        phone: '{!! $phone !!}',
        email: '{!! $email !!}',
        address: '{!! $address !!}',
        city: '{!! $city !!}',
        notes: '{!! $notes !!}',
        VATRequired: '{!! $VATRequired !!}',
        errors: ''
    },
    methods: {
        onSubmit() {
            console.log("GG");
            axios.put('/businesses/{!! $business->id !!}/customers/{!! $customer->id !!}', {
                name: this.name,
                phone: this.phone,
                email: this.email,
                address: this.address,
                city: this.city,
                notes: this.notes,
                VATRequired: this.VATRequired                          
            }).then(response => {
                this.name = '',
                this.phone = '',
                this.email = '',
                this.address = '',
                this.city = '',
                this.notes = '',
                this.VATRequired = '',
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