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