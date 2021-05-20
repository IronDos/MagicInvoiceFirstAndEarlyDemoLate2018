<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLineInInvoice extends Model
{
    protected $table = 'product_line_in_invoices';

    public $primaryKey = 'id';

    protected $fillable = [
        'invoice_id',
        'product_record_id',
        'currency_id',
        'productPrice',
        'quantity',
        'totalPrice',
        'VATRequired',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function productRecord()
    {
        return $this->belongsTo('App\ProductRecord');
    }
}
