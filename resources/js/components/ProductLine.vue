<template>
    <div>
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
                <tr v-for="(product, index) in products" :key="product.pName">
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
                        v-on:click="removeElement(index);"></i>
                    </th>
                </tr>
            </tbody>
        </table>
        <button type="button"
        class="btn btn-primary"
        v-on:click="addproduct">הוסף מוצר</button>
    </div>
    
</template>

<script>
export default {
    name: 'ProductLine',
    data: function(){
        return {
            products: [{
                pName: "",
                pQuantity: "1",
                pPrice: "",
                pTotalPrice: ""
            }],
            errors: [],
        }
    },
    methods: {
        addproduct() {
            var elem = document.createElement('tr');
            this.products.push({
                pName: "",
                pQuantity: "1",
                pPrice: "",
                pTotalPrice: ""
            });
        },
        removeElement(index) {
            this.products.splice(index, 1);
        },
        sumproduct(index) {
            this.products[index].pTotalPrice = this.products[index].pQuantity * this.products[index].pPrice;
        },
        onSubmit() {
            axios.post('/tests', {
                h1: this.h1,
                products: this.products
            }).then(response => {
                this.h1 = ''
                this.products = [{
                    pName: "",
                    pQuantity: "1",
                    pPrice: "",
                    pTotalPrice: ""
                }]
            }).catch(error => {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
            })
        }
    }
}
</script>

