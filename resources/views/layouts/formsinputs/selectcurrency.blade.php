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