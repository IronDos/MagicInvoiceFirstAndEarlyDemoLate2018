<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Business;
use App\Currency;
use App\Rules\SelectCurrency;
use Illuminate\Validation\Rule;

use App\InvoiceType;
use App\VAT;
use App\DocsNumbering;

class BusinessesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusinessAndUser')->except('index', 'create', 'store');
        $this->middleware('CheckMaxBusinesses')->only('create', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title ='דף הבית של בתי העסק';
        $businesses = Business::all();
        return view('businesses.index', compact('title', 'businesses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bTypeValue = Auth::user()->plan->bType;
        // $bTypeValue = 'AuthorizedDealer';
        if ($bTypeValue == 'ExemptDealer') {$title = 'צור עסק פטור חדש';}
        if ($bTypeValue == 'AuthorizedDealer') {$title = 'צור עסק מורשה חדש';}

        $email = Auth::user()->email;
        $currencies = Currency::all();
        
        return view('businesses.create', compact(
            'title',
            'bTypeValue',
            'email',
            'currencies'
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
        //dd($_POST);
        $request->validate([
            'name' => 'required|unique:businesses|max:250',
            'businessTaxIdNumber' => 'required|unique:businesses|max:250',
            'phone' => 'required|unique:businesses|digits_between:1,20',
            'fax' => 'nullable|digits_between:0,20|unique:businesses',
            'email' => 'required|unique:businesses|max:250|email',
            'address' => 'required|max:250',
            'city' => 'required|max:250',
            'website' => 'nullable||max:250|unique:businesses',
            'subTitle' => 'max:250',
            'selectedCurrency' => ['bail', 'required', new SelectCurrency],
            'VAT' => 'required|digits_between:0,100',
            'notes' => 'max:250'
        ]);

        if ($request->input('bType') == 'ExemptDealer')
        {
            $business = Business::create([
                'user_id' => Auth::id(),
                'businessTaxIdNumber' => $request->input('businessTaxIdNumber'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'website' => $request->input('website'),
                'subTitle' => $request->input('subTitle'),
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'notes' => $request->input('notes')
            ]);
        }

        if ($request->input('bType') == 'AuthorizedDealer')
        {
            $business = Business::create([
                'user_id' => Auth::id(),
                'businessTaxIdNumber' => $request->input('businessTaxIdNumber'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'website' => $request->input('website'),
                'subTitle' => $request->input('subTitle'),
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'notes' => $request->input('notes')
            ]);
        }

        DocsNumbering::create([
            'business_id' => $business->id,
            'draft' => 0,
            'draftVAT' => 100000,
            'invoice' => 200000,
            'invoiceVAT' => 300000,
            'receipt' => 400000,
            'receiptVAT' => 500000,
            'invoiceReceipt' => 600000,
            'invoiceVATReceipt' => 700000,
            'creditInvoice' => 800000,
            'shippingCertificate' => 900000,
            'returnCertificate' => 1000000,
            'order' => 1100000,
        ]);

        // $request->validate([
        //     'name' => 'required|max:1',
        //     'businessTaxIdNumber' => 'required|max:1',
        //     'phone' => 'required|max:1',
        //     'fax' => 'max:1',
        //     'email' => 'required|max:1|email',
        //     'address' => 'required|max:1',
        //     'city' => 'required|max:1',
        //     'website' => 'required|max:1',
        //     'subTitle' => 'required|max:1',
        //     'selectedCurrency' => ['bail', 'required', new SelectCurrency],
        //     'vat' => 'required|max:1',
        //     'notes' => 'required|max:1'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $title = "הצגת העסק " . $business->name;
        $invoiceTypes = InvoiceType::all();
        return view('businesses.show', compact('title', 'business', 'invoiceTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        
        $title = "ערוך עסק";
        $bTypeValue = Auth::user()->plan->bType;
        $business = Business::find($request->route()->parameters('business')['business']);
        $currencies = Currency::all();
        $id = $business->id;
        $name = $business->name;
        $businessTaxIdNumber = $business->businessTaxIdNumber;
        $phone = $business->phone;
        $fax = $business->fax;
        $email = $business->email;
        $address = $business->address;
        $city = $business->city;
        $website = $business->website;
        $subTitle = $business->subTitle;
        $selectedCurrency = $business->currency->name;
        $VAT = VAT::where('endDate', null)->first()->percentage;
        $notes = $business->notes;

        return view('businesses.edit', compact(
            'title',
            'bTypeValue',
            'currencies',
            'id',
            'name',
            'businessTaxIdNumber',
            'phone',
            'fax',
            'email',
            'address',
            'city',
            'website',
            'subTitle',
            'selectedCurrency',
            'VAT',
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
        $business = Business::find($request->route()->parameters('business')['business']);
        $request->validate([
            'name' => [
                'required',
                'max:250',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'businessTaxIdNumber' => [
                'required',
                'max:250',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'phone' => [
                'required',
                'digits_between:1,20',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'fax' => [
                'nullable',
                'digits_between:0,20',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'email' => [
                'required',
                'max:250',
                'email',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'address' => [
                'required',
                'max:250'
            ],
            'city' => [
                'required',
                'max:250'
            ],
            'website' => [
                'nullable',
                'max:250',
                Rule::unique('businesses')->ignore($business->id)
            ],
            'subTitle' => [
                'max:250'
            ],
            'selectedCurrency' => [
                'bail',
                'required',
                new SelectCurrency
            ],
            'notes' => [
                'max:250'
            ]
        ]);

        if (Auth::user()->plan->bType == 'ExemptDealer')
        {
            $business->update([
                'businessTaxIdNumber' => $request->input('businessTaxIdNumber'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'website' => $request->input('website'),
                'subTitle' => $request->input('subTitle'),
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'notes' => $request->input('notes')
            ]);
        }

        if (Auth::user()->plan->bType == 'AuthorizedDealer')
        {
            $request->validate([
                'VAT' => [
                    'required',
                    'digits_between:0,100'
                ]
            ]);
            $business->update([
                'businessTaxIdNumber' => $request->input('businessTaxIdNumber'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'website' => $request->input('website'),
                'subTitle' => $request->input('subTitle'),
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'notes' => $request->input('notes')
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $business->delete();
        return redirect()->action('BusinessesController@index');
    }


    // Start DocsNumbering
    public function docsNumbering(Request $request)
    {
        
        $title = 'ספרור מסמכים';
        $business = Business::find($request->route()->parameters('business')['business']);
        
        $docsNumbering = $business->docsNumberings->first();
        $docsTypes = [];
        $disabled = $business->docsNumberings->first()->GetDisabledDocs();

        if ($business->user->plan->bType == 'ExemptDealer') {
            $docsTypes = [
                'Draft' => $business->docsNumberings->first()->docsCounter('Draft'),
                'Invoice' => $business->docsNumberings->first()->docsCounter('Invoice'),
                'InvoiceReceipt' => $business->docsNumberings->first()->docsCounter('InvoiceReceipt'),
                'Receipt' => $business->docsNumberings->first()->docsCounter('Receipt'),
            ];
        }

        if ($business->user->plan->bType == 'AuthorizedDealer') {
            $docsTypes = [
                'DraftVAT' => $business->docsNumberings->first()->docsCounter('DraftVAT'),
                'InvoiceVAT' => $business->docsNumberings->first()->docsCounter('InvoiceVAT'),
                'InvoiceVATReceipt' => $business->docsNumberings->first()->docsCounter('InvoiceVATReceipt'),
                'Receipt' => $business->docsNumberings->first()->docsCounter('Receipt'),
            ];
        }

        // $docsNumbering = $business->docsNumberings->first()->docsCounter('DraftVAT');


        return view('businesses.docsnumberings', compact(
            'business',
            'title',
            'docsTypes',
            'disabled'
        ));
    } // Start DocsNumbering

    // Start storeDocsNumbering
    public function storeDocsNumbering(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $docsNumbering = $business->docsNumberings->first();

        if ($business->user->plan->bType == 'ExemptDealer') {
            $request->validate([
                'Draft' => 'required|numeric',
                'Invoice' => 'required|numeric',
                'InvoiceReceipts' => 'required|numeric',
                'Receipt' => 'required|numeric'
            ]);

            $docsNumberToUpdate = [
                'Draft' => $request->input('Draft'),
                'Invoice' => $request->input('Invoice'),
                'InvoiceReceipt' => $request->input('InvoiceReceipt'),
                'Receipt' => $request->input('Receipt'),
            ];

            foreach ($docsNumberToUpdate as $key => $docNumberToUpdate) {
                $counter = $docsNumbering->GetInvoicesByTypeName($key)->count();
                if ($counter == 0) {
                    $docsNumbering->update([
                        $key => $docNumberToUpdate
                    ]);
                }
            }
        }

        if ($business->user->plan->bType == 'AuthorizedDealer') {
            $request->validate([
                'DraftVAT' => 'required|numeric',
                'InvoiceVAT' => 'required|numeric',
                'InvoiceVATReceipt' => 'required|numeric',
                'Receipt' => 'required|numeric'
            ]);

            $docsNumberToUpdate = [
                'DraftVAT' => $request->input('DraftVAT'),
                'InvoiceVAT' => $request->input('InvoiceVAT'),
                'InvoiceVATReceipt' => $request->input('InvoiceVATReceipt'),
                'Receipt' => $request->input('Receipt'),
            ];

            foreach ($docsNumberToUpdate as $key => $docNumberToUpdate) {
                $counter = $docsNumbering->GetInvoicesByTypeName($key)->count();
                if ($counter == 0) {
                    $docsNumbering->update([
                        $key => $docNumberToUpdate
                    ]);
                }
            }
        }


    }
}
