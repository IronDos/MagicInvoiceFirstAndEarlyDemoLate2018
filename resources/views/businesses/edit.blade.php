@extends('layouts.app')

@section('content')
{{$bTypeValue}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5>{{$title}}</h5></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="app">
                        <form method="POST"
                            action="/businesses"
                            v-on:submit.prevent="onSubmit">
                        @csrf
                        @method('PUT')
                            <h5>פרטי העסק</h5>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">שם העסק</label>
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
                                    v-if="errors.name"
                                    v-bind:class="{'invalid-feedback': errors.name}">
                                        <strong>שם העסק אינו תקין</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="businessTaxIdNumber" class="col-md-4 col-form-label text-md-right">מספר עוסק</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="businessTaxIdNumber"
                                        name="businessTaxIdNumber"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.businessTaxIdNumber }"
                                        required autofocus
                                        v-model="businessTaxIdNumber">
    
                                    <span
                                    role="alert"
                                    v-if="errors.businessTaxIdNumber"
                                    v-bind:class="{'invalid-feedback': errors.businessTaxIdNumber}">
                                        <strong>מספר עוסק לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">טלפון</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="phone"
                                        name="phone"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.phone }"
                                        required autofocus
                                        v-model="phone">
    
                                    <span
                                    role="alert"
                                    v-if="errors.phone"
                                    v-bind:class="{'invalid-feedback': errors.phone}">
                                        <strong>טלפון לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="fax" class="col-md-4 col-form-label text-md-right">פקס</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="fax"
                                        name="fax"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.fax }"
                                        autofocus
                                        v-model="fax">
    
                                    <span
                                    role="alert"
                                    v-if="errors.fax"
                                    v-bind:class="{'invalid-feedback': errors.fax}">
                                        <strong>פקס לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">אימייל</label>
                                <div class="col-md-6">
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.phone }"
                                        required autofocus
                                        v-model="email">
    
                                    <span
                                    role="alert"
                                    v-if="errors.email"
                                    v-bind:class="{'invalid-feedback': errors.email}">
                                        <strong>אימייל לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">כתובת</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="address"
                                        name="address"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.address }"
                                        required autofocus
                                        v-model="address">
    
                                    <span
                                    role="alert"
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
                                        required autofocus
                                        v-model="city">
    
                                    <span
                                    role="alert"
                                    v-if="errors.city"
                                    v-bind:class="{'invalid-feedback': errors.city}">
                                        <strong>העיר אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="website" class="col-md-4 col-form-label text-md-right">אתר אינטרנט</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="website"
                                        name="website"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.website }"
                                        autofocus
                                        v-model="website">
    
                                    <span
                                    role="alert"
                                    v-if="errors.website"
                                    v-bind:class="{'invalid-feedback': errors.website}">
                                        <strong>כתובת האתר אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>
    
                            <h5>תוכן קבוע למסמכים</h5>
    
                            <div class="form-group row">
                                <label for="subTitle" class="col-md-4 col-form-label text-md-right">תת כותרת</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="subTitle"
                                        name="subTitle"
                                        class="form-control"
                                        required autofocus
                                        placeholder="יופיע מתחת לשם העסק"
                                        v-model="subTitle">
    
                                    <span
                                    role="alert"
                                    v-if="errors.name"
                                    v-bind:class="{'invalid-feedback': errors.subTitle}">
                                        <strong>תת הכותרת אינה תקינה</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="selectedCurrency" class="col-md-4 col-form-label text-md-right">סוג מטבע</label>
                                <div class="col-md-6">
                                    <select name="selectedCurrency"
                                            id="selectedCurrency"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.selectedCurrency }"
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
    
                            <div class="form-group row" v-if="bType === 'AuthorizedDealer'">
                                <label for="VAT" class="col-md-4 col-form-label text-md-right">מע"מ באחוזים</label>
                                <div class="col-md-6">
                                    <input type="number"
                                        id="VAT"
                                        name="VAT"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.VAT }"
                                        min="0"
                                        max="100"
                                        required autofocus
                                        readonly
                                        v-model="VAT">
                                    <span
                                    role="alert"
                                    v-if="errors.VAT"
                                    class="invalid-feedback" >
                                        <strong>מעמ לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="notes" class="col-md-4 col-form-label text-md-right">הערות</label>
                                <div class="col-md-6">
                                    <textarea name="notes"
                                        id="notes"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.notes }"
                                        cols="30" rows="2"
                                        placeholder='יופיע בתחתית המסמך'
                                        v-model="notes"></textarea>
    
                                    <span
                                    role="alert"
                                    v-if="errors.notes"
                                    class="invalid-feedback">
                                        <strong>הערות אינן תקינות</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        עדכן פרטי עסק
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
    el: '#app',
    data: {
        bType: '{!! $bTypeValue !!}',
        currencies: {!! $currencies !!},
        name: '{!! $name !!}',
        businessTaxIdNumber: '{!! $businessTaxIdNumber !!}',
        phone: '{!! $phone !!}',
        fax: '{!! $fax !!}',
        email: '{!! $email !!}',
        address: '{!! $address !!}',
        city: '{!! $city !!}',
        website: '{!! $website !!}',
        subTitle: '{!! $subTitle !!}',
        selectedCurrency: '{!! $selectedCurrency !!}',
        VAT: '{!! $VAT !!}' * 100,
        notes: '{!! $notes !!}',
        errors: ''
    },
    methods: {
        onSubmit() {
            axios.put('/businesses/{!! $id !!}', {
                name: this.name,
                businessTaxIdNumber: this.businessTaxIdNumber,
                phone: this.phone,
                fax: this.fax,
                email: this.email,
                address: this.address,
                city: this.city,
                website: this.website,
                subTitle: this.subTitle,
                selectedCurrency: this.selectedCurrency,
                VAT: this.VAT,
                notes: this.notes,
                bType: this.bType
            }).then(response => {
                this.name = '',
                this.businessTaxIdNumber = '',
                this.phone = '',
                this.fax = '',
                this.email = '',
                this.address = '',
                this.city = '',
                this.website = '',
                this.subTitle = '',
                this.selectedCurrency = '',
                this.VAT = '',
                this.notes = '',
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

