<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRecord extends Model
{
    protected $table = 'products_records';

    public $primaryKey = 'id';

    protected $fillable = [
        'currency_id',
        'product_id',
        'name',
        'price',
        'VATRequired',
        'quantity',
    ];

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }




}
