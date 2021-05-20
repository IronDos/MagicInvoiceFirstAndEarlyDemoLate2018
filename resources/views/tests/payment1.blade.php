@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <div id="app">
                        <form action="/tests"
                        method="post"
                        v-on:submit.prevent="onSubmit">
                            <div id="payments" v-for="(payment, index) in payments">
                                <div class="form-group row">
                                    <label for="selectPaymentMethod" class="col-md-4 col-form-label text-md-right">אמצעי תשלום</label>
                                    <div class="col-md-6">
                                        <select name="selectPaymentMethod[]"
                                                id="selectPaymentMethod[]"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.selectPaymentMethod }"
                                                v-model="payment.paymentMethod">
                                            <option value=""></option>
                                            <option value="CreditCard">כרטיס אשראי</option>
                                            <option value="Cheque">צ'ק</option>
                                            <option value="BankTransfer">העברה בנקאית</option>
                                            <option value="Cash">מזומן</option>
                                        </select>
                                        <span
                                        role="alert"
                                        v-if="errors.selectPaymentMethod"
                                        v-bind:class="{'invalid-feedback': errors.selectPaymentMethod}">
                                            <strong>אמצעי תשלום לא תקין</strong>
                                        </span>
                                    </div>
                                </div>
    
                                <!-- Date Relevet for all type of SelectPaymentsMethods-->
                                <div class="form-group row" v-if="paymentMethod != ''">
                                    <label for="creditCardDate" class="col-md-4 col-form-label text-md-right">תאריך</label>
                                    <div class="col-md-6">
                                        <input type="date"
                                            id="creditCardDate"
                                            name="creditCardDate"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.creditCardDate }"
                                            required autofocus
                                            v-model="creditCardDate">
        
                                        <span
                                        role="alert"
                                        v-if="errors.creditCardDate"
                                        v-bind:class="{'invalid-feedback': errors.creditCardDate}">
                                            <strong>תאריך לא תקין</strong>
                                        </span>
                                    </div>
                                </div>
    
                                <!-- CreditCard Div -->
                                <div class="form-group" v-if="paymentMethod === 'CreditCard'">
                                    <div class="form-group row">
                                        <label for="creditCardLastFourNumbers" class="col-md-4 col-form-label text-md-right">4 ספרות אחרונות</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                id="creditCardLastFourNumbers"
                                                name="creditCardLastFourNumbers"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.creditCardLastFourNumbers }"
                                                min="0000"
                                                max="9999"
                                                required autofocus
                                                v-model="creditCardLastFourNumbers">
            
                                            <span
                                            role="alert"
                                            v-if="errors.creditCardLastFourNumbers"
                                            v-bind:class="{'invalid-feedback': errors.creditCardLastFourNumbers}">
                                                <strong>4 ספרות אחרונות של כרטיס האשראי אינן תקינות</strong>
                                            </span>
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <label for="selectCreditCardType" class="col-md-4 col-form-label text-md-right">סוג הכרטיס</label>
                                        <div class="col-md-6">
                                            <select name="selectCreditCardType"
                                                    id="selectCreditCardType"
                                                    class="form-control"
                                                    v-bind:class="{ 'is-invalid': errors.selectCreditCardType }"
                                                    v-model="creditCardType">
                                                <option value=""></option>
                                                <option value="Visa">ויזה</option>
                                                <option value="AmericanExpress">אמריקן אקספרס</option>
                                                <option value="MasterCard">מאסטרקארד</option>
                                                <option value="Diners">דיינרס</option>
                                                <option value="Isracard">ישראכרט</option>
                                                <option value="Other">אחר</option>
                                            </select>
    
                                            <span
                                            role="alert"
                                            v-if="errors.creditCardType"
                                            v-bind:class="{'invalid-feedback': errors.creditCardType}">
                                                <strong>סוג הכרטיס אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="selectCreditCardType" class="col-md-4 col-form-label text-md-right">סוג העסקה</label>
                                        <div class="col-md-6">
                                            <select name="selectCreditCardTransactionType"
                                                    id="selectCreditCardTransactionType"
                                                    class="form-control"
                                                    v-bind:class="{ 'is-invalid': errors.creditCardTransactionType }"
                                                    v-model="creditCardTransactionType">
                                                <option value=""></option>
                                                <option value="Regular">רגיל</option>
                                                <option value="DeferredCharge">חיוב דחוי</option>
                                                <option value="Installment">תשלומים</option>
                                                <option value="Credit">קרדיט</option>
                                                <option value="Other">אחר</option>
                                            </select>
                                            <span
                                            role="alert"
                                            v-if="errors.creditCardTransactionType"
                                            v-bind:class="{'invalid-feedback': errors.creditCardTransactionType}">
                                                <strong>סוג העסקה אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
    
                                    <div class="form-group row" v-if="creditCardTransactionType === 'Installment'">
                                        <label for="installmentNumber" class="col-md-4 col-form-label text-md-right">מספר תשלומים</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                id="installmentNumber"
                                                name="installmentNumber"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.installmentNumber }"
                                                min="0000"
                                                max="1000"
                                                required autofocus
                                                v-model="installmentNumber">
            
                                            <span
                                            role="alert"
                                            v-if="errors.installmentNumber"
                                            v-bind:class="{'invalid-feedback': errors.installmentNumber}">
                                                <strong>מספר התשלומים אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- End CreditCard Div -->
    
    
                                <!-- Start Bank Info -->
                                <div class="form-group" v-if="paymentMethod === 'Cheque' || paymentMethod === 'BankTransfer'">
                                    <div class="form-group row">
                                        <label for="bankId" class="col-md-4 col-form-label text-md-right">מס' בנק</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                id="bankId"
                                                name="bankId"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.bankId }"
                                                min="10"
                                                max="99"
                                                required autofocus
                                                v-model="bankId">
            
                                            <span
                                            role="alert"
                                            v-if="errors.bankId"
                                            v-bind:class="{'invalid-feedback': errors.bankId}">
                                                <strong>מס' הבנק אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
                                        
                                    <div class="form-group row">
                                        <label for="bankBranchId" class="col-md-4 col-form-label text-md-right">מס' סניף</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                id="bankBranchId"
                                                name="bankBranchId"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.bankBranchId }"
                                                min="100"
                                                max="999"
                                                required autofocus
                                                v-model="bankBranchId">
            
                                            <span
                                            role="alert"
                                            v-if="errors.bankBranchId"
                                            v-bind:class="{'invalid-feedback': errors.bankBranchId}">
                                                <strong>מס' הסניף אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <label for="bankAccountId" class="col-md-4 col-form-label text-md-right">מס' חשבון</label>
                                        <div class="col-md-6">
                                            <input type="number"
                                                id="bankAccountId"
                                                name="bankAccountId"
                                                class="form-control"
                                                v-bind:class="{ 'is-invalid': errors.bankAccountId }"
                                                min="0"
                                                max="999999999"
                                                required autofocus
                                                v-model="bankAccountId">
            
                                            <span
                                            role="alert"
                                            v-if="errors.bankAccountId"
                                            v-bind:class="{'invalid-feedback': errors.bankAccountId}">
                                                <strong>מס' החשבון אינו תקין</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Bank Info -->
    
                                <!-- Start Cheque Div -->
                                <div class="form-group row" v-if="paymentMethod === 'Cheque'">
                                    <label for="chequeId" class="col-md-4 col-form-label text-md-right">מס' צ'ק</label>
                                    <div class="col-md-6">
                                        <input type="number"
                                            id="chequeId"
                                            name="chequeId"
                                            class="form-control"
                                            v-bind:class="{ 'is-invalid': errors.chequeId }"
                                            min="0"
                                            max="999999999"
                                            required autofocus
                                            v-model="chequeId">
        
                                        <span
                                        role="alert"
                                        v-if="errors.chequeId"
                                        v-bind:class="{'invalid-feedback': errors.chequeId}">
                                            <strong>מס' הצ'ק אינו תקין</strong>
                                        </span>
                                    </div>
                                </div>
                                <!-- End Cheque Div -->
                            </div>
                            

                            <!-- Start of Payments Total and Currency -->
                            <div class="form-group row" v-if="paymentMethod != ''">
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

                            <div class="form-group row" v-if="paymentMethod != ''">
                                <label for="paymentTotal" class="col-md-4 col-form-label text-md-right">סכום</label>
                                <div class="col-md-6">
                                    <input type="number"
                                        id="paymentTotal"
                                        name="paymentTotal"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.paymentTotal }"
                                        min="0000"
                                        max="1000"
                                        required autofocus
                                        v-model="paymentTotal">
    
                                    <span
                                    role="alert"
                                    v-if="errors.paymentTotal"
                                    v-bind:class="{'invalid-feedback': errors.paymentTotal}">
                                        <strong>סכום אינו תקין</strong>
                                    </span>
                                </div>
                            </div>
                            <!-- End of Payments Total and Currency-->
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
        payments: [{
            paymentMethod: '',
            creditCardDate: '',
            creditCardLastFourNumbers: '',
            creditCardType: '',
            creditCardTransactionType: '',
            installmentNumber: '',
            bankId: '',
            bankBranchId: '',
            chequeId: '',
            paymentTotal: '',
        }],
        currencies: '',
        selectedCurrency: '',
        errors: ''
    },
    methods: {
        addPayment() {
            var elem = document.createElement('tr');
            this.payments.push({
                paymentMethod: '',
                creditCardDate: '',
                creditCardLastFourNumbers: '',
                creditCardType: '',
                creditCardTransactionType: '',
                installmentNumber: '',
                bankId: '',
                bankBranchId: '',
                chequeId: '',
                paymentTotal: ''
            });
        }
    }
});
</script>
@endsection