<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerRecord extends Model
{
    protected $table = 'customers_records';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'customer_id',
        'email',
        'phone',
        'city',
        'address',
        'notes',
        'VATRequired'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function receipts()
    {
        return $this->hasMany('App\Receipt');
    }
}
