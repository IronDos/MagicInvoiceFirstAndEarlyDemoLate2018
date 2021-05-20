<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    protected $table = 'invoice_types';

    public $primaryKey = 'id';

    protected $fillable = [
        'name', 'title', 'bType',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
