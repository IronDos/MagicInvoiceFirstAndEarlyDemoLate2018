@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">הירשם</div>

                <div class="card-body">
                    <div id="app">
                        <form method="POST"
                            action="{{ route('register') }}"
                            v-on:submit.prevent="onSubmit">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">שם</label>
    
                                <div class="col-md-6">
                                    <input type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    v-bind:class="{ 'is-invalid': errors.name }"
                                    value="{{ old('name') }}"
                                    required autofocus
                                    v-model="name">
    
                                    <span
                                    role="alert"
                                    v-if="errors.name"
                                    v-bind:class="{'invalid-feedback': errors.name}">
                                        <strong>השם אינו תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">כתובת אימייל</label>
    
                                <div class="col-md-6">
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.email }"
                                        value="{{ old('email') }}"
                                        required
                                        v-model="email">
    
                                    <span
                                    role="alert"
                                    v-if="errors.email"
                                    v-bind:class="{'invalid-feedback': errors.email}">
                                        <strong>אימייל נמצא בשימוש או לא תקין</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="israeliId" class="col-md-4 col-form-label text-md-right">תעודת זהות</label>
                                <div class="col-md-6">
                                    <input type="text"
                                        id="israeliId"
                                        name="israeliId"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.israeliId }"
                                        value="{{ old('israeliId') }}"
                                        required autofocus
                                        v-model="israeliId">
    
                                    <span
                                    role="alert"
                                    v-if="errors.israeliId"
                                    v-bind:class="{'invalid-feedback': errors.israeliId}">
                                        <strong>תעודת הזהות חייבת להכיל 9 ספרות ולהיות תקינה</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="bType" class="col-md-4 col-form-label text-md-right">סוג העסק</label>
                                <div class="col-md-6">
                                    <select name="bType"
                                            id="bType"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.plansAndBType }"
                                            v-model="bType">
                                        <option value="" {{old('bType') === "" ? 'selected' : ''}}></option>
                                        <option value="ExemptDealer" {{old('bType') === "ExemptDealer" ? 'selected' : ''}}>עוסק פטור</option>
                                        <option value="AuthorizedDealer" {{old('bType') === "AuthorizedDealer" ? 'selected' : ''}}>עוסק מורשה</option>
                                    </select>

                                    <span
                                    role="alert"
                                    v-if="errors.plansAndBType"
                                    v-bind:class="{'invalid-feedback': errors.plansAndBType}">
                                        <strong>סוג העסק לא תקין/חבילה לא תקינה</strong>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group row" v-if="bType === 'ExemptDealer'">
                                <label for="bType" class="col-md-4 col-form-label text-md-right">בחר חבילה</label>
                                <div class="col-md-6">
                                    <select name="exemptDealerPlan"
                                            id="exemptDealerPlan"
                                            class="form-control{{ $errors->has('exemptDealerPlan') ? ' is-invalid' : '' }}"
                                            v-model="exemptDealerPlan">
                                        <option value="" {{old('exemptDealerPlan') === "" ? 'selected' : ''}}></option>
                                        <option value="DocsOnly" {{old('exemptDealerPlan') === "DocsOnly" ? 'selected' : ''}}>הפקת מסמכים ללא הגשת דוחות</option>
                                        <option value="DocsAndReports" {{old('exemptDealerPlan') === "DocsAndReports" ? 'selected' : ''}}>הפקת מסמכים והגשת דוחות שנתיים ע"י רואה חשבון יועץ מס</option>
                                    </select>
                                    @if ($errors->has('exemptDealerPlan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>חובה לבחור חבילה</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row" v-if="bType === 'AuthorizedDealer'">
                                <label for="bType" class="col-md-4 col-form-label text-md-right">בחר חבילה</label>
                                <div class="col-md-6">
                                    <select name="authorizedDealerPlan"
                                            id="authorizedDealerPlan"
                                            class="form-control{{ $errors->has('authorizedDealerPlan') ? ' is-invalid' : '' }}"
                                            v-model="authorizedDealerPlan">
                                        <option value="" {{old('authorizedDealerPlan') === "" ? 'selected' : ''}}></option>
                                        <option value="DocsOnly" {{old('authorizedDealerPlan') === "DocsOnly" ? 'selected' : ''}}>הפקת מסמכים ללא הגשת דוחות שנתיים</option>
                                        <option value="DocsAndReports" {{old('authorizedDealerPlan') === "DocsAndReports" ? 'selected' : ''}}>הפקת מסמכים כולל הגשת דוחות שנתיים ע"י רואה חשבון יועץ מס</option>
                                        <option value="DocReportsAndRepresentation" {{old('authorizedDealerPlan') === "DocReportsAndRepresentation" ? 'selected' : ''}}>הפקת מסמכים, דוחות שנתיים ע"י רואה חשבון יועץ מס ויצוג מול הרשויות הרלוונטיות</option>
                                    </select>
                                    @if ($errors->has('authorizedDealerPlan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>חובה לבחור חבילה</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">סיסמה</label>
    
                                <div class="col-md-6">
                                    <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    v-bind:class="{ 'is-invalid': errors.password }"
                                    required
                                    v-model="password">
    
                                    <span
                                    role="alert"
                                    class="invalid-feedback"
                                    v-if="errors.password"
                                    v-bind:class="{'invalid-feedback': errors.password}">
                                        <strong>סיסמה לא תקינה צריכה להיות מינימום 6 תווים</strong>
                                    </span>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">אמת סיסמה</label>
    
                                <div class="col-md-6">
                                    <input type="password"
                                    id="password-confirm"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                    v-model="password_confirmation">
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        הירשם
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
        name: '',
        email: '',
        israeliId: '',
        bType: '',
        exemptDealerPlan: '',
        authorizedDealerPlan: '',
        password: '',
        password_confirmation: '',
        errors: ''
    },
    methods: {
        onSubmit() {
            axios.post('/register', {
                name: this.name,
                email: this.email,
                israeliId: this.israeliId,
                bType: this.bType,
                exemptDealerPlan: this.exemptDealerPlan,
                authorizedDealerPlan: this.authorizedDealerPlan,
                password: this.password,
                password_confirmation: this.password_confirmation,
                plansAndBType: [this.bType, this.exemptDealerPlan, this.authorizedDealerPlan]
            }).then(response => {
                this.name = '',
                this.email = '',
                this.israeliId = '',
                this.bType = '',
                this.exemptDealerPlan = '',
                this.authorizedDealerPlan = '',
                this.password = '',
                this.password_confirmation = '',
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
