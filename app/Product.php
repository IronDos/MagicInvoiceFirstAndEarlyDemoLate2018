<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'currency_id',
        'business_id',
        'price',
        'VATRequired',
        'quantity',
        'show',
    ];

    public function business()
    {
        return $this->belongsTo('App\Business');
    }
    
    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function productsRecords()
    {
        return $this->hasMany('App\ProductRecord');
    }

    public function getProductRecord()
    {
        $productRecord = $this->productsRecords
        ->where('name', $this->name)
        ->where('currency_id', $this->currency->id)
        ->where('price', $this->price)
        ->where('VATRequired', $this->VATRequired)
        ->where('quantity', $this->quantity);
    }

    public function createProductRecord()
    {
        ProductRecord::create([
            'product_id' => $this->id,
            'currency_id' => $this->currency->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'VATRequired' => $this->VATRequired,
        ]);
    }

    public static function SumProductByCurrency($product, $currencyName, $dateString)
    {
        $sum = 0;
        $totalPrice = Discount::GetPriceAfterDiscount($product['pTotalPrice'], $product['pDiscountType'], $product['pDiscountAmount']);
        $currencies = Currency::GetCurrenciesByDate($dateString);

        foreach ($currencies as $key => $currency) {
            if ($currency['name'] == $product['pSelectedCurrency'])
            {
                $productCurrencyRate = $currency['rate'];
                $productCurrencyUnit = $currency['unit'];
            }

            if ($currency['name'] == $currencyName)
            {
                $currencyRate = $currency['rate'];
                $currencyUnit = $currency['unit'];
            }
        }

        $totalPrice = ($productCurrencyRate / $currencyRate) * $totalPrice;
        $totalPrice = $totalPrice / ($productCurrencyUnit / $currencyUnit);
        return $totalPrice;
    }

    public static function SumProductsByCurrency($products, $currencyName, $dateString)
    {
        $sum = 0;
        foreach ($products as $key => $product) {
            $sum += $this->SumProductByCurrency($product, $currencyName, $dateString);
        }

        return $sum;
    }
}
