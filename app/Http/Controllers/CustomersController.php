<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Business;
use App\Customer;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusiness')->only('index', 'create', 'store');
        $this->middleware('CheckCustomerAndBusiness')->except('index', 'create', 'store'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'ניהול לקוחות';
        $business = Business::find($request->route()->parameters('business')['business']);
        $customers = $business->customers->where('show', 'Yes');
        return view('customers.index', compact('title', 'business', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'צור לקוח חדש';
        $business = Business::find($request->route()->parameters('business')['business']);
        return view('customers.create', compact('title', 'business'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        if ($business != NULL) {
            if ($business->user->id == Auth::id()) {
                // Set VATRequired
                if (Auth::user()->plan->bType == 'AuthorizedDealer' &&
                    $request->input('VATRequired') == true ) {
                        $VATRequired = 'Yes';
                } else { $VATRequired = 'No'; }
                
                $request->validate([
                    'name' => 'required|max:250|unique:customers',
                    'phone' => 'nullable|max:20|digits_between:1,20|unique:customers',
                    'email' => 'nullable|max:250|email|unique:customers',
                    'address' => 'max:250',
                    'city' => 'max:250',
                    'notes' => 'max:250'
                ]);
                $customer = Customer::create([
                    'business_id' => $business->id,
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'city' => $request->input('city'),
                    'VATRequired' => $VATRequired,
                    'show' => 'Yes',
                    'notes' => $request->input('notes'),
                ]);
                $customer->createCustomerRecord();
            } else { return false; }
            
        } else { return false; }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $customer = Customer::find($request->route()->parameters('customer')['customer']);
        $business = $customer->business;
        $title = 'הצגת לקוח ' . $customer->name;
        $docs=[];

        if ($customer->invoices() != null) {
            foreach ($customer->invoices() as $key => $invoice) {
                $docs[] = $invoice;
            } return dd($customer->invoices());
        }
        if ($customer->receipts() != null) {
            foreach ($customer->receipts() as $key => $receipt) {
                $docs[] = $receipt;
            }
        }
        
        

        for ($i=0; $i < count($docs)-1; $i++) { 
            for ($j=0; $j < count($docs)-1; $j++) { 
                //return dd($docs[$j]->date);
                if (($docs[$j]->date) < ($docs[$j+1]->date)) {
                    $temp = $docs[$j];
                    $docs[$j] = $docs[$j+1];
                    $docs[$j+1] = $temp;
                }
            }
        }

        return view('customers.show', compact(
            'title',
            'business',
            'customer',
            'docs'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $customer = Customer::find($request->route()->parameters('customer')['customer']);
        $business = $customer->business;
        $name = $customer->name;
        $title = 'ערוך לקוח ' . $name;
        $phone =$customer->phone;
        $email = $customer->email;
        $address = $customer->address;
        $city = $customer->city;
        
        $VATRequired = $customer->VATRequired;
        if ($VATRequired == 'Yes') { $VATRequired = true; }
        else {$VATRequired = false;}
        
        $notes = $customer->notes;
        //dd($phone);

        return view('customers.edit', compact(
            'title',
            'business',
            'customer',
            'name',
            'phone',
            'email',
            'address',
            'city',
            'VATRequired',
            'notes'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $customer = Customer::find($request->route()->parameters('customer')['customer']);

        $request->validate([
            'name' => [
                'required',
                'max:250',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'phone' => [
                'nullable',
                'max:250',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'email' => [
                'nullable',
                'email',
                'max:250',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'address' => [
                'nullable',
                'max:250',
            ],
            'city' => [
                'nullable',
                'max:250',
            ],
            'notes' => [
                'nullable',
                'max:250',
            ],
        ]);

        // Set VATRequired
        if (Auth::user()->plan->bType == 'AuthorizedDealer' &&
            $request->input('VATRequired')  == true) {
                $VATRequired = 'Yes';
        } else { $VATRequired = 'No'; }

        $customer->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'VATRequired' => $VATRequired,
            'notes' => $request->input('notes'),
        ]);
        $customer->createCustomerRecord();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $customer = Customer::find($request->route()->parameters('customer')['customer']);
        $customer->delete();
        return redirect()->action('CustomersController@index');
    }
}
