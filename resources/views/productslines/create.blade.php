<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">שם המוצר</th>
            <th scope="col">כמות</th>
            <th scope="col">מחיר ליחידה</th>
            <th scope="col">מחיר שורה</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(product, index) in products">
            <th scope="row"> 
                <input type="text"
                v-model="product.pName"
                name="pName[]"
                id="pName[]"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors['products.' + index + '.pName'] }">                                            
            </th>
            <th>
                <input type="number"
                v-model="product.pQuantity"
                v-on:input="sumproduct(index)"
                name="pQuantity[]"
                id="pQuantity[]"
                class="form-control"
                min="1"
                step="any">
            </th>
            <th>
                <input type="number"
                v-model="product.pPrice"
                v-on:input="sumproduct(index)"
                name="pPrice[]"
                id="pPrice[]"
                class="form-control"
                min="0"
                step="any">
            </th>
            <th>
                <input type="number"
                v-model="product.pTotalPrice"
                name="pTotalPrice[]"
                id="pTotalPrice[]"
                class="form-control"
                min="0"
                readonly
                step="any">
            </th>
            <th>
                <i class="fas fa-times"
                v-on:click="removeLineInInvoice(index);"></i>
            </th>
        </tr>
    </tbody>
</table>
<button type="button"
class="btn btn-primary"
v-on:click="addproduct">הוסף מוצר</button>

