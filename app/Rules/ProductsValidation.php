<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;
use App\Business;
use App\Product;
use App\Discount;
use App\Currency;

use App\Rules\DiscountValidation;
use App\Rules\SelectCurrency;

class ProductsValidation implements Rule
{
    protected $business;
    // protected $invoiceDiscount;
    // protected $invoiceTotalPriceBeforeVAT;
    // protected $VAT;
    // protected $invoiceTotalPriceVAT;

    // Product Errors
    protected $productTypeErrors;
    protected $productNameErrors;
    protected $newProductNameErrors;
    protected $productQuantityErrors;
    protected $productPriceErrors;
    protected $productTotalPriceErrors;
    protected $discountErrors;
    protected $productCurrencyErrors;
    protected $errors;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($business)
    {
        $this->business = Business::find($business);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->business != null ) {
            $this->business = $this->business->first();
            $products = $value;
            return $this->ProductsValidation($products);
        } else {
            return false;
        }

        // if ($this->productTypeErrors == '' &&
        //     $this->productNameErrors == '' &&
        //     $this->newProductNameErrors == '' &&
        //     $this->productQuantityErrors == '' &&
        //     $this->productPriceErrors == '' &&
        //     $this->productTotalPriceErrors == '') {
        //         return true;
        // } else {
        //     $this->errors = [
        //         'productNameErrors' => $this->productNameErrors,
        //         'productQuantityErrors' => $this->productQuantityErrors,
        //         'productPriceErrors' => $this->productPriceErrors,
        //         'productTotalPriceErrors' => $this->productTotalPriceErrors,
        //         'newProductNameErrors' =>$this->newProductNameErrors,
        //         'productTypeErrors' => $this->productTypeErrors,
        //         'discountErrors' =>$this->discountErrors
        //     ];
        // }
        // return false;
        
    }

    public function ProductsValidation($products)
    {
        foreach ($products as $key => $product) {
            $this->ProductValidation($key, $product);
        }

        if ($this->productTypeErrors == '' &&
            $this->productNameErrors == '' &&
            $this->newProductNameErrors == '' &&
            $this->productQuantityErrors == '' &&
            $this->productPriceErrors == '' &&
            $this->productTotalPriceErrors == '' &&
            $this->discountErrors == '' &&
            $this->productCurrencyErrors == '') {
                return true;
        } else {
            $this->errors = [
                'productNameErrors' => $this->productNameErrors,
                'productQuantityErrors' => $this->productQuantityErrors,
                'productPriceErrors' => $this->productPriceErrors,
                'productTotalPriceErrors' => $this->productTotalPriceErrors,
                'newProductNameErrors' =>$this->newProductNameErrors,
                'productTypeErrors' => $this->productTypeErrors,
                'discountErrors' => $this->discountErrors,
                'productCurrencyErrors' => $this->productCurrencyErrors
            ];
        }
        return false;
    }


    public function ProductValidation($key, $product)
    {
        // Start Type&Name
        $this->ProductTypeAndNameValidation($product['pType'], $product['newProductName']);
        //End Type&Name

        // Start Currency
        $this->ProductCurrencyValidation($product['selectedCurrency']);
        //End Currency

        // Start Quantity
        $this->ProductQuantityValidation($product['pQuantity']);
        //End Quantity

        // Start Price&TotalPrice
        $this->ProductTotalPriceValidation($key, $product['pQuantity'], $product['pPrice'], $product['pTotalPrice']);
        //End Price&TotalPrice

        // Start Discount
        $this->ProductDiscountValidation($product['pDiscountType'], $product['pDiscountAmount']);
        //End Discount


    }

    public function ProductTypeAndNameValidation($key, $productType, $productName)
    {
        if ($productType == 'NewProduct') {
            $searchProduct = new Product([
                'name' => $product['newProductName']
            ]);
            $validator = Validator::make(
                ['name' => $product['newProductName']],
                ['name' => 'required|string|max:250|unique:products']
            );
            if ($validator->fails()) {
                $this->newProductNameErrors[$key] = 'error';
            }
        } elseif ($productType == 'ExistingProduct') {
            $searchProduct = Product::find($productName);
            if ($searchProduct == null) {
                $this->productNameErrors[$key] = 'error';
            }
        } else {
            $this->productTypeErrors[$key] = 'error';
        }
    }

    public function ProductCurrencyValidation($key, $currencyName)
    {
        $validator = Validator::make(
            ['selectedCurrency' => $currencyName],
            ['selectedCurrency' => [new SelectCurrency]]
        );

        if ($validator->fails()) {
            $this->productCurrencyErrors[$key] = 'error';
        }
    }

    public function ProductQuantityValidation($key, $productName, $productQuantity)
    {
        if (!is_int($productQuantity) || $prodcutQuantity < 0) {
            $this->productQuantityErrors[$key];
        }
        
        $searchProduct = Product::find($productName);
        if ($searchProduct != null) {
            if ($searchProduct->quantity != null) {
                if ($searchProduct->quantity > $productQuantity) {
                    $this->productQuantityErrors[$key];
                }
            }
        }

    }

    public function ProductTotalPriceValidation($key, $prodcutQuantity, $productPrice, $productTotalPrice)
    {
        if (!is_numeric($productPrice) || $productPrice <= 0) {
            $this->productPriceErrors[$key];
        }

        if (!is_numeric($productTotalPrice) || $productTotalPrice <= 0) {
            $this->productTotalPriceErrors[$key];
        }

        if ($productPrice * $prodcutQuantity != $productTotalPrice)
        {
            $this->productTotalPriceErrors[$key];
        }
    }

    public function ProductDiscountValidation($key, $discountType, $discountAmount)
    {
        $validator = Validator::make(
            ['discount' => $discountAmount],
            ['discount' => [
                'nullable',
                'numeric',
                new DiscountValidation($discountType)
                ]
            ]
        );
        if ($validator->fails()) {
            $this->discountErrors[$key] = 'error';
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errors;
    }
}
