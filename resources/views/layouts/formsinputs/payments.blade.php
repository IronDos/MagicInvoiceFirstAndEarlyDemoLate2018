<div id="paymentrow" v-for="(payment, index) in payments">
    <h5>אמצעי תשלום @{{ index+1 }}</h5>
    <h6 v-on:click="RemovePayment(index);" class="red">הסר</h6>
    <div class="form-group row">
        <label for="selectMethod" class="col-md-4 col-form-label text-md-right">אמצעי תשלום</label>
        <div class="col-md-6">
            <select name="selectMethod[]"
                    id="selectMethod[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': selectMethodErrors[index]}"
                    v-model="payment.selectMethod">
                <option value=""></option>
                <option value="CreditCard">כרטיס אשראי</option>
                <option value="Cheque">צ'ק</option>
                <option value="BankTransfer">העברה בנקאית</option>
                <option value="Cash">מזומן</option>
            </select>
            <span
            role="alert"
            v-if="selectMethodErrors[index]"
            v-bind:class="{'invalid-feedback': selectMethodErrors[index]}">
                <strong>@{{ selectMethodErrors[index] }}</strong>
            </span>
        </div>
    </div>
    
    <!-- Start Date -->
    <div class="form-group row" v-if="payment.selectMethod != ''">
        <label for="date" class="col-md-4 col-form-label text-md-right">תאריך</label>
        <div class="col-md-6">
            <input type="date"
                id="date[]"
                name="date[]"
                class="form-control"
                v-bind:class="{ 'is-invalid': dateErrors[index] }"
                required autofocus
                v-model="payment.date">

            <span
            role="alert"
            v-if="dateErrors[index]"
            v-bind:class="{'invalid-feedback': dateErrors[index]}">
                <strong>@{{ dateErrors[index] }}</strong>
            </span>
        </div>
    </div>
    <!-- End Date -->

    <!-- Stat CreditCard -->
    <div class="form-group" v-if="payment.selectMethod === 'CreditCard'">
        <div class="form-group row">
            <label for="creditCardLastFourNumbers" class="col-md-4 col-form-label text-md-right">4 ספרות אחרונות</label>
            <div class="col-md-6">
                <input type="number"
                    id="creditCardLastFourNumbers[]"
                    name="creditCardLastFourNumbers[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': creditCardLastFourNumbersErrors[index] }"
                    min="0000"
                    max="9999"
                    required autofocus
                    v-model="payment.creditCardLastFourNumbers">

                <span
                role="alert"
                v-if="creditCardLastFourNumbersErrors[index]"
                v-bind:class="{'invalid-feedback': creditCardLastFourNumbersErrors[index]}">
                    <strong>@{{creditCardLastFourNumbersErrors[index]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="selectCreditCardType" class="col-md-4 col-form-label text-md-right">סוג הכרטיס</label>
            <div class="col-md-6">
                <select name="selectCreditCardType[]"
                        id="selectCreditCardType[]"
                        class="form-control"
                        v-bind:class="{ 'is-invalid': selectCreditCardTypeErrors[index] }"
                        v-model="payment.selectCreditCardType">
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
                v-if="selectCreditCardTypeErrors[index]"
                v-bind:class="{'invalid-feedback': selectCreditCardTypeErrors[index]}">
                    <strong>@{{selectCreditCardTypeErrors[index]}}</strong>
                </span>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="selectCreditCardType" class="col-md-4 col-form-label text-md-right">סוג העסקה</label>
            <div class="col-md-6">
                <select name="selectCreditCardTransactionType[]"
                        id="selectCreditCardTransactionType[]"
                        class="form-control"
                        v-bind:class="{ 'is-invalid': creditCardTransactionTypeErrors[index] }"
                        v-model="payment.creditCardTransactionType">
                    <option value=""></option>
                    <option value="Regular">רגיל</option>
                    <option value="DeferredCharge">חיוב דחוי</option>
                    <option value="Installment">תשלומים</option>
                    <option value="Credit">קרדיט</option>
                    <option value="Other">אחר</option>
                </select>
                <span
                role="alert"
                v-if="creditCardTransactionTypeErrors[index]"
                v-bind:class="{'invalid-feedback': creditCardTransactionTypeErrors[index]}">
                    <strong>@{{creditCardTransactionTypeErrors[index]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row" v-if="payment.creditCardTransactionType === 'Installment'">
            <label for="installmentNumber" class="col-md-4 col-form-label text-md-right">מספר תשלומים</label>
            <div class="col-md-6">
                <input type="number"
                    id="installmentNumber[]"
                    name="installmentNumber[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': installmentNumberErrors[index] }"
                    min="0000"
                    max="1000"
                    required autofocus
                    v-model="payment.installmentNumber">

                <span
                role="alert"
                v-if="installmentNumberErrors[index]"
                v-bind:class="{'invalid-feedback': installmentNumberErrors[index]}">
                    <strong>@{{ installmentNumberErrors[index] }}</strong>
                </span>
            </div>
        </div>
    </div>
    <!-- End CreditCard Div -->

    <!-- Start Bank Info --> 
    <div class="form-group" v-if="payment.selectMethod === 'Cheque' || payment.selectMethod === 'BankTransfer'">
        <div class="form-group row">
            <label for="bankId" class="col-md-4 col-form-label text-md-right">מס' בנק</label>
            <div class="col-md-6">
                <input type="number"
                    id="bankId[]"
                    name="bankId[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': bankIdErrors[index] }"
                    min="10"
                    max="99"
                    required autofocus
                    v-model="payment.bankId">

                <span
                role="alert"
                v-if="bankIdErrors[index]"
                v-bind:class="{'invalid-feedback': bankIdErrors[index]}">
                    <strong>@{{bankIdErrors[index]}}</strong>
                </span>
            </div>
        </div>
            
        <div class="form-group row">
            <label for="bankBranchId" class="col-md-4 col-form-label text-md-right">מס' סניף</label>
            <div class="col-md-6">
                <input type="number"
                    id="bankBranchId[]"
                    name="bankBranchId[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid':bankBranchIdErrors[index] }"
                    min="100"
                    max="999"
                    required autofocus
                    v-model="payment.bankBranchId">

                <span
                role="alert"
                v-if="bankBranchIdErrors[index]"
                v-bind:class="{'invalid-feedback': bankBranchIdErrors[index]}">
                    <strong>@{{bankBranchIdErrors[index]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="bankAccountId" class="col-md-4 col-form-label text-md-right">מס' חשבון</label>
            <div class="col-md-6">
                <input type="number"
                    id="bankAccountId[]"
                    name="bankAccountId[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': bankAccountIdErrors[index] }"
                    min="0"
                    max="999999999"
                    required autofocus
                    v-model="payment.bankAccountId">

                <span
                role="alert"
                v-if="bankAccountIdErrors[index]"
                v-bind:class="{'invalid-feedback': bankAccountIdErrors[index]}">
                    <strong>@{{bankAccountIdErrors[index]}}</strong>
                </span>
            </div>
        </div>
    </div>
    <!-- End Bank Info -->

    <!-- Start Cheque Div -->
    <div class="form-group row" v-if="payment.selectMethod === 'Cheque'">
            <label for="chequeId" class="col-md-4 col-form-label text-md-right">מס' צ'ק</label>
            <div class="col-md-6">
                <input type="number"
                    id="chequeId[]"
                    name="chequeId[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': chequeIdErrors[index] }"
                    min="0"
                    max="999999999"
                    required autofocus
                    v-model="payment.chequeId">

                <span
                role="alert"
                v-if="chequeIdErrors[index]"
                v-bind:class="{'invalid-feedback': chequeIdErrors[index]}">
                    <strong>@{{chequeIdErrors[index]}}</strong>
                </span>
            </div>
        </div>
        <!-- End Cheque Div -->

        <!-- Start PaymentTotal Div -->
        <div class="form-group row" v-if="payment.selectMethod != ''">
            <label for="paymentTotal" class="col-md-4 col-form-label text-md-right">סכום</label>
            <div class="col-md-6">
                <input type="number"
                    id="paymentTotal[]"
                    name="paymentTotal[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': paymentTotalErrors[index] }"
                    min="0000"
                    step="any"
                    required autofocus
                    v-on:input="SumPaymentsTotal()"
                    v-model="payment.paymentTotal">

                <span
                role="alert"
                v-if="paymentTotalErrors[index]"
                v-bind:class="{'invalid-feedback': paymentTotalErrors[index]}">
                    <strong>@{{paymentTotalErrors[index]}}</strong>
                </span>
            </div>
        </div>
        <!-- End PaymentTotal Div -->
</div>

<!-- Start PaymentsTotal Div -->
<div class="form-group row" v-if="payments != ''">
    <label for="paymentTotal" class="col-md-4 col-form-label text-md-right">סכום</label>
    <div class="col-md-6">
        <input type="number"
            id="paymentTotal[]"
            name="paymentTotal[]"
            class="form-control"
            v-bind:class="{ 'is-invalid': paymentsTotalErrors }"
            min="0"
            step="any"
            required autofocus
            readonly
            v-model="paymentsTotal">

        <span
        role="alert"
        v-if="paymentsTotalErrors"
        v-bind:class="{'invalid-feedback': paymentsTotalErrors}">
            <strong>@{{paymentsTotalErrors}}</strong>
        </span>
    </div>
</div>
<!-- End PaymentsTotal Div -->
<div class="form-group">
    <button type="button"
    class="btn btn-primary"
    v-on:click="AddPayment">
        הוסף אמצעי תשלום
    </button>
</div>

<div class="form-group">
    <div
    role="alert"
    v-if="errors.paymentsTotal"
    v-bind:class="{'alert alert-danger text-center' : errors.paymentsTotal}">
        @{{ errors.paymentsTotal[0] }}
    </div>
</div>
