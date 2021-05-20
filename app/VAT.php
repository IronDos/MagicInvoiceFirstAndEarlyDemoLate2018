<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VAT extends Model
{
    protected $table = 'vat';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'percentage',
        'startDate',
        'endDate',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
