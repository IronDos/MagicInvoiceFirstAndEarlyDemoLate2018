<div class="form-group ">
    <label for="selectedCustomer" class="col-md-4 col-form-label text-md-right">בחר סוג</label>
    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio"
        id="customRadioInline1"
        name="customerType"
        class="custom-control-input"
        value="ExistingCustomer"
        v-model="customerType">
        <label class="custom-control-label" for="customRadioInline1">לקוח מרשימת הלקוחות</label>
    </div>
    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio"
        id="customRadioInline2"
        name="customerType"
        class="custom-control-input"
        v-model="customerType"
        value="NewCustomer">
        <label class="custom-control-label" for="customRadioInline2">לקוח חדש</label>
    </div>
    <span v-if="errors.selectedCustomer"
    class="red">
        נא מלא לקוח באופן תקין
    </span>
</div>

<div class="form-group row" v-if="customerType === 'ExistingCustomer'">
    <label for="selectedCustomer" class="col-md-4 col-form-label text-md-right">בחר הלקוח</label>
    <div class="col-md-6">
        <select name="selectedCustomer"
                id="selectedCustomer"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors.selectedCustomer }"
                v-model="selectedCustomer">
            <option value=""></option>
            <option v-for="customer in customers" v-bind:value="customer.id">@{{ customer.name }}</option>
        </select>
        <span
        role="alert"
        v-if="errors.selectedCustomer"
        v-bind:class="{'invalid-feedback': errors.selectedCustomer}">
            <strong>בחר לקוח תקין</strong>
        </span>
    </div>
</div>

<div class="form-group row" v-if="customerType == 'NewCustomer'">
    <label for="customerName"
        class="col-md-4 col-form-label text-md-right">שם הלקוח</label>
    <div class="col-md-6">
        <input type="text"
        id="customerName"
        name="customerName"
        class="form-control"
        v-bind:class="{ 'is-invalid': errors.selectedCustomer}"
        required autofocus
        v-model="customerName">

        <div class="custom-control custom-checkbox my-1 mr-sm-2">
            <input type="checkbox"
            id="customControlInline"
            class="custom-control-input"
            v-model="saveCustomer">
            <label class="custom-control-label" for="customControlInline">הוסף לקוח למאגר הלקוחות</label>
        </div>

        <span
        role="alert"
        class="invalid-feedback"
        v-if="errors.selectedCustomer"
        v-bind:class="{'invalid-feedback': errors.selectedCustomer}">
            <strong>שם לקוח תפוס/לא תקין</strong>
        </span>
    </div>
</div>