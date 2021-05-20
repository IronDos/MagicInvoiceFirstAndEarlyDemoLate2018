@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    <div id="app">
                        <form method="POST"
                        action="/tests"
                        v-on:submit.prevent="onSubmit"
                        class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group form-row">
                                    <label for="h1"
                                    class="col-md-4 col-form-label text-md-right">H1</label>
                                    <div class="col-md-6">
                                        <input type="text"
                                        name="h1"
                                        id="h1"
                                        class="form-control"
                                        v-bind:class="{ 'is-invalid': errors.h1 }"
                                        v-model="h1">
                                    </div>
                                    <span
                                    role="alert"
                                    v-if="errors.h1"
                                    v-bind:class=" {'invalid-feedback': errors.h1}">
                                         להזין מייל</strong>
                                    </span>
                            </div>

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
                                            v-on:click="removeElement(index);"></i>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button"
                            class="btn btn-primary"
                            v-on:click="addproduct">הוסף מוצר</button>
                            
                            <br><br>
                            <div class="form-group product mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        צור קבלה
                                    </button>
                                </div>
                            </div>
                        </form>
                        @{{ errors['products.index.pName'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            h1: "",
            products: [{
                pName: "",
                pQuantity: "1",
                pPrice: "",
                pTotalPrice: ""
            }],
            errors: [],
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
            },
            getClass(val, index) {
                return '';
            }
        }
    });
</script>
@endsection