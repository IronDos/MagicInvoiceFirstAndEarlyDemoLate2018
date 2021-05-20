<div class="form-group row">
    <label for="startDate" class="col-md-4 col-form-label text-md-right">מתאריך</label>
    <div class="col-md-6">
        <input type="date"
            id="startDate"
            name="startDate"
            class="form-control"
            v-bind:class="{ 'is-invalid': errors.startDate }"
            required autofocus
            @change="VATReport()"
            v-model="startDate">

        <span
        role="alert"
        v-if="errors.startDate"
        v-bind:class="{'invalid-feedback': errors.startDate}">
            <strong>@{{ errors.startDate }}</strong>
        </span>
    </div>
</div>

<div class="form-group row">
    <label for="endDate" class="col-md-4 col-form-label text-md-right">עד תאריך</label>
    <div class="col-md-6">
        <input type="date"
            id="endDate"
            name="endDate"
            class="form-control"
            v-bind:class="{ 'is-invalid': errors.endDate }"
            required autofocus
            @change="VATReport()"
            v-model="endDate">

        <span
        role="alert"
        v-if="errors.endDate"
        v-bind:class="{'invalid-feedback': errors.endDate}">
            <strong>@{{ errors.endDate }}</strong>
        </span>
    </div>
</div>