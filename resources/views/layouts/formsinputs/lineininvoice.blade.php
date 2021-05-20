<h4>הוסף מוצרים</h4>
<div v-for="(product, index) in products">
        <hr style="height: 2px; border:none; background-color:#4286f4;">
    <div class="row">
        <div class="col-md">
            <div class="row">
                <h6>בחר מוצר</h6>
            </div>
            <div class="form-group" v-bind:id="'chooseProductType' + index">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio"
                    v-bind:id="'productTypeExisting' + index"
                    v-bind:name="'productTypeExisting' + index"
                    class="custom-control-input"
                    v-bind:class="{ 'is-invalid':pTypeErrors[index] }"
                    value="ExistingProduct"
                    v-model="product.pType">
                    <label class="custom-control-label" v-bind:for="'productTypeExisting' + index">רשימת מוצרים</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio"
                    v-bind:id="'productTypeNew' + index"
                    v-bind:name="'productTypeNew' + index"
                    class="custom-control-input"
                    v-bind:class="{ 'is-invalid':pTypeErrors[index] }"
                    value="NewProduct"
                    v-model="product.pType">
                    <label class="custom-control-label" v-bind:for="'productTypeNew' + index">מוצר חדש</label>
                </div>
            </div>
                
            <div class="form-group" v-if="product.pType === 'ExistingProduct'">
                <div class="col-md-12">
                    <select name="pName"
                    id="pName"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': pNameErrors[index]}"
                    v-model="product.pName"
                    v-on:change="getPrice(product.pName, index)">
                        <option value="">בחר מוצר</option>
                        <option v-for="availableProduct in availableProducts" v-bind:value="availableProduct.id">@{{ availableProduct.name }}</option>
                        <option value="עד מתי">עד מתי</option>
                    </select>
                </div>
            </div>
                
            <div class="form-group row" v-if="product.pType == 'NewProduct'">
                <div class="col-md-12">
                    <input type="text"
                    id="newProductName"
                    name="newProductName"
                    class="form-control"
                    v-bind:class="{ 'is-invalid':newProductNameErrors[index] }"
                    required autofocus
                    v-model="product.newProductName">
            
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox"
                        :id="'customControlInline' + index"
                        :name="'customControlInline' + index"
                        class="custom-control-input"
                        v-model="product.pSave">
                        <label class="custom-control-label" :for="'customControlInline' + index">הוסף מוצר למאגר</label>
                    </div>
            
                    <span
                    role="alert"
                    class="invalid-feedback"
                    v-if="newProductNameErrors[index]"
                    v-bind:class="{'invalid-feedback': newProductNameErrors[index]}">
                        <strong>שם המוצר לא תקין/תפוס</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" v-if = "product.pType != ''">
                <label for="selectedCurrency">סוג מטבע</label>
                <div class="col">
                    <select
                        class="form-control"
                        v-bind:class="{ 'is-invalid': errors.selectedCurrency }"
                        v-on:change="sumInvoiceByCurrency()"
                        v-model="product.pSelectedCurrency">
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

            <div class="custom-control custom-checkbox my-1 mr-sm-2" v-if="mixed == true">
                <input type="checkbox"
                :id="'customControlInlineVAT' + index"
                :name="'customControlInlineVAT' + index"
                class="custom-control-input"
                @change="sumInvoice()"
                v-model="product.pVATRequired">
                <label class="custom-control-label" :for="'customControlInlineVAT' + index">מוצר חייב במע"מ</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
                <h6>כמות</h6>
            </div>
            <input type="number"
            id="pQuantity[]"
            name="pQuantity[]"
            class="form-control"
            v-bind:class="{ 'is-invalid': pQuantityErrors[index]}"
            min="1"
            step="any"
            v-model="product.pQuantity"
            v-on:input="sumProduct(index, 'price')">
        </div>
        <div class="col-md-2">
            <div class="row">
                <h6>מחיר יחידה</h6>
            </div>
            <input type="number"
            id="pPrice[]"
            name="pPrice[]"
            class="form-control"
            v-bind:class="{ 'is-invalid': pPriceErrors[index]}"
            min="-10"
            step="0.01"
            v-model="product.pPrice"
            v-on:input="sumProduct(index, 'price')">
        </div>
        <div class="col-md-3">
            <div class="row">
                <h6>מחיר שורה</h6>
            </div>
            <input type="number"
            id="pTotalPrice[]"
            name="pTotalPrice[]"
            class="form-control"
            v-bind:class="{ 'is-invalid': pTotalPriceErrors[index]}"
            min="0"
            v-on:input="sumProduct(index, 'totalPrice')"
            step="0.01"
            v-model="product.pTotalPrice">

            <label for="pDiscount">הנחת שורה</label>
            <div class="input-group">
                <select
                id="productDiscont"
                name="productDiscont"
                class="custom-select"
                v-on:input="sumProduct(index, 'totalPrice')"
                v-model="product.pDiscountType">
                    <option value="Percentage">%</option>
                    <option value="Money">כמות</option>
                </select>
                <input type="text"
                class="form-control"
                aria-describedby="basic-addon3"
                v-on:input="sumProduct(index, 'totalPrice')"
                v-model="product.pDiscountAmount">
            </div>
            
            <div v-if="product.pDiscountAmount > 0">
                <label for="pTotalPriceAfterDiscount">מחיר שורה לאחר הנחה</label>
                <input type="number"
                    id="pTotalPrice[]"
                    name="pTotalPrice[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': pTotalPriceErrors[index]}"
                    min="0"
                    step="0.01"
                    readonly
                    v-model="product.pTotalPriceAfterDiscount">
            </div>
            

            <div v-if="product.pSelectedCurrency != selectedCurrency">
                <label for="pTotalPriceAfterDiscount">מחיר שורה במטבע המסמך</label>
                <input type="number"
                    id="pTotalPrice[]"
                    name="pTotalPrice[]"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': pTotalPriceErrors[index]}"
                    min="0"
                    step="0.01"
                    readonly
                    v-model="product.pTotalPriceRow">
            </div>

            <div class="form-group input-group" v-if="bType === 'AuthorizedDealer'">
                <div class="input-group-prepend">
                    <span class="input-group-text">+מע"מ</span>
                </div>
                <div class="input-group-prepend">
                    <span class="input-group-text col-md-12">
                        @{{ product.pTotalPriceRowAndVAT }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-2 co-sm-12">
            <p class="red text-center" v-on:click="removeLineInInvoice(index);" >
                הסר שורה
                <i class="fas fa-times red justify"
                title="מחק שורה"></i>
            </p>
        </div>
    </div>
</div>
<div class="formg-group row">
    <button type="button"
        class="btn btn-primary"
        v-on:click="addproduct">
        הוסף שורה
    </button>
</div>
<br>
<div class="form-group">
    <div role="alert"
    v-if="errors.products"
    class="alert alert-danger text-center">
        חובה שתהיה שורה אחת לפחות במסמך
    </div>
</div>
