<div class="form-group row">
    <label for="date" class="col-md-4 col-form-label text-md-right">תאריך</label>
    <div class="col-md-6">
        <input type="date"
            id="date"
            name="date"
            class="form-control"
            v-bind:class="{ 'is-invalid': errors.date }"
            v-bind:min="minDate"
            required autofocus
            v-model="date">

        <span
        role="alert"
        v-if="errors.date"
        v-bind:class="{'invalid-feedback': errors.date}">
            <strong>@{{ errors.date }}</strong>
        </span>
    </div>
</div>