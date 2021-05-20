<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $table = 'invoice_statuses';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'status',
        'title',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
