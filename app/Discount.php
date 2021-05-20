<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    public $primaryKey = 'id';

    protected $fillable = [
        'invoice_id',
        'product_line_in_invoices_id',
        'type',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function productLineInInvoice()
    {
        return $this->belongsTo('App\ProductLineInInvoice');
    }

    public static function GetPriceAfterDiscount($price, $discountType, $discountAmount)
    {
        if ($discountType == 'Money') {
            return $price - $discountAmount;
        } elseif ($discountType == 'Percentage') {
            return $price - (($discountAmount / 100) * $price);
        } else {
            return 0;
        }
    }
}
