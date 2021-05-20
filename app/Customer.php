<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'business_id',
        'email',
        'phone',
        'city',
        'address',
        'notes',
        'show',
        'VATRequired'
    ];

    public function business() { return $this->belongsTo('App\Business'); }

    public function customersRecords()
    {
        return $this->hasMany('App\CustomerRecord');
    }

    public function getCustomerRecord()
    {
        $customerRecord = $this->customersRecords
        ->where('name', $this->name)
        ->where('email', $this->email)
        ->where('phone', $this->phone)
        ->where('city', $this->city)
        ->where('address', $this->address)
        ->where('notes', $this->notes)
        ->where('VATRequired', $this->VATRequired);
        if ($customerRecord != null) { return $customerRecord->first(); }
        return null;
    } 

    public function invoices()
    {
        $customersRecords = $this->customersRecords();
        // $invoices = $this->customersRecords->filter(function($customerRecord) {
        //     return $customerRecord->invoices();
        // });
        // return dd($invoices);
        $invoices = [];
        if ($customersRecords != null) {
            foreach ($customersRecords as $key => $customerRecord) {
                if ($customerRecord->invoices != null) {
                    $invoices[] = $customerRecord->invoices;
                }
            }
            if ($invoices != null) { return $invoices; }
        }
        return null;
    }

    public function receipts()
    {
        // $receipts = $this->customersRecords->filter(function($customerRecord) {
        //     return $customerRecord->receipts();
        // });
        // return $receipts;
        $customersRecords = $this->customersRecords();
        $receipts = [];
        if ($customersRecords != null) {
            foreach ($customersRecords as $key => $customerRecord) {
                if ($customerRecord->receipts != null) {
                    $receipts[] = $customerRecord->receipts;
                }
            }
            if ($receipts != null) { return $receipts; }
        }
        return null;
    }

    public function createCustomerRecord()
    {
        $customersRecords = $this->customersRecords();
        if ($customersRecords != null) {
            CustomerRecord::create([
                'customer_id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'city' => $this->city,
                'address' => $this->address,
                'notes' => $this->notes,
                'VATRequired' => $this->VATRequired
            ]);
        }
    }

    public function updateCustomerRecord()
    {
        $customerRecord = $this->customersRecords();
    }
}
