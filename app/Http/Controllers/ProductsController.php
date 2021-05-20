<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Business;
use App\Product;
use App\VAT;
use App\Currency;

use App\Rules\SelectCurrency;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckProductAndBusiness')->except('index', 'create', 'store');
        $this->middleware('CheckBusiness')->only('index', 'create', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'ניהול מוצרים';
        $business = Business::find($request->route()->parameters('business')['business']);
        $products = $business->products->sortBy('name')->where('show', 'Yes');
        return view('products.index', compact('title', 'business', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'צור מוצר חדש';
        $business = Business::find($request->route()->parameters('business')['business']);
        $VAT = VAT::where('endDate', null)->first()->percentage;
        $VAT += 1;
        $currencies = Currency::all();
        $selectedCurrency = Business::find($request->route()->parameters('business')['business'])->currency->name;

        return view('products.create', compact(
            'title',
            'business',
            'VAT',
            'currencies',
            'selectedCurrency'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return dd($request->input('selectedCurrency'));
        $business = Business::find($request->route()->parameters('business')['business']);
        if ($business != NULL) {
            if ($business->user->id == Auth::id()) {
                $request->validate([
                    'name' => 'required|string|max:250|unique:products',
                    'quantity' => 'nullable|integer',
                    'price' => 'nullable|min:0|numeric',
                    'priceAfterVAT' => 'nullable|min:0|numeric',
                    'selectedCurrency' => new SelectCurrency
                ]);
                
                // Set VATRequired
                if ($request->input('VATRequired') == true) {
                    $VATRequired = 'yes';
                } else { $VATRequired = 'No'; }

                $product = Product::create([
                    'business_id' => $business->id,
                    'name' => $request->input('name'),
                    'quantity' => $request->input('quantity'),
                    'price' => $request->input('price'),
                    'show' => 'Yes',
                    'VATRequired' => $VATRequired,
                    'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                ]);
                $product->createProductRecord();
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
        $product = Product::find($request->route()->parameters('product')['product']);
        $business = $product->business;
        $title = 'הצגת מוצר ' . $product->name;
        return view('products.show', compact('title', 'business', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $product = Product::find($request->route()->parameters('product')['product']);
        $business = $product->business;
        $name = $product->name;
        $title = 'ערוך מוצר ' . $name;
        $price = $product->price;
        $quantity = $product->quantity;
        $VAT = VAT::where('endDate', null)->first()->percentage;
        $VAT += 1;
        $currencies = Currency::all();
        $selectedCurrency = Business::find($request->route()->parameters('business')['business'])->currency->name;

        $VATRequired = $product->VATRequired;
        if ($VATRequired == 'Yes') { $VATRequired = true; }
        else { $VATRequired = false; }

        
        

        return view('products.edit', compact(
            'title',
            'business',
            'product',
            'name',
            'price',
            'quantity',
            'VAT',
            'currencies',
            'selectedCurrency',
            'VATRequired'
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
        $product = Product::find($request->route()->parameters('product')['product']);

        $request->validate([
            'name' => [
                'required',
                'max:250',
                Rule::unique('products')->ignore($product->id)
            ],
            'price' => [
                'nullable',
                'min:0',
                'numeric'
            ],
            'quantity' => [
                'nullable',
                'integer'
            ],

            'priceAfterVAT' => 'nullable|min:0|numeric',
            'selectedCurrency' => new SelectCurrency
        ]);
        
        // Set VATRequired
        if ($request->input('VATRequired') == true) {
            $VATRequired = 'yes';
        } else { $VATRequired = 'No'; }

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'show' => 'Yes',
            'VATRequired' => $VATRequired,
            'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
        ]);
        $product->createProductRecord();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::find($request->route()->parameters('product')['product']);
        $product->delete();
        //return redirect()->action('ProductsController@index($request)');
        return back();
    }
}
