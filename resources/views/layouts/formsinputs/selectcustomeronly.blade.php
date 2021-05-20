<div class="form-group row">
    <label for="selectedCustomer" class="col-md-4 col-form-label text-md-right">בחר לקוח או השאר רק לכל הלקוחות</label>
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