<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Business;
use App\Customer;
use App\Product;
use App\Currency;
use App\Invoice;
use App\InvoiceType;
use App\InvoiceStatus;
use App\VAT;

use App\Rules\SelectCurrency;
use App\Rules\CustomerValidation;
use App\Rules\ProductsValidation;
use App\Rules\InvoiceTotalPriceValidation;
use App\Rules\InvoiceTotalPriceAndPaymentsVaildation;
use App\Rules\PaymentsValidation;
use App\Rules\DiscountValidation;

use App\Rules\InvoiceValidation;

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductLineInInvoicesController;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckInvoiceAndBusiness')->except('index', 'create', 'store');
        $this->middleware('CheckBusiness')->only('index', 'create', 'store', 'edit', 'update', 'distroy', 'show');
        $this->middleware('CheckInvoiceType')->only('create', 'store');
        $this->middleware('CheckInvoiceForEdit')->only('edit', 'update');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'ניהול חשבוניות';
        $business = Business::find($request->route()->parameters('business')['business']);
        $invoices = $business->invoices;
        $business = $business;
        $invoiceTypes = InvoiceType::all();
        return view('invoices.index', compact(
            'title',
            'business',
            'invoices',
            'invoiceTypes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Auth::user()->plan->bType == 'ExemptDealer') {
            $bType = 'ExemptDealer';
        }
        if (Auth::user()->plan->bType == 'AuthorizedDealer') {
            $bType = 'AuthorizedDealer';
        }

        $date = date("Y-m-d");
        $title = 'צור ' . InvoiceType::find($request->route()->parameters('invoiceType')['invoiceType'])->title;
        $customers = Business::find($request->route()->parameters('business')['business'])->customers;
        $availableProducts = Business::find($request->route()->parameters('business')['business'])->products;
        
        // Set Currencies
        Currency::UpdateCurrencies();
        $currencies = Currency::all();
        $selectedCurrency = Business::find($request->route()->parameters('business')['business'])->currency->name;
        $currencyDate = date_create(Currency::all()->first()->updated_at);
        $currencyDate = date_format($currencyDate, 'Y-m-d');

        $VAT = VAT::where('endDate', null)->first()->percentage;
        $notes = Business::
        find($request->route()->parameters('business')['business'])->notes;

        $business = Business::find($request->route()->parameters('business')['business']);
        
        $invoiceType = InvoiceType::find($request->route()->parameters('invoiceType')['invoiceType']);
        if ($invoiceType->name == 'MixedDraft' ||
            $invoiceType->name == 'MixedInvoice' ||
            $invoiceType->name == 'MixedInvoiceReceipt' ||
            $invoiceType->name == 'MixedDraftVAT' ||
            $invoiceType->name == 'MixedInvoiceVAT' ||
            $invoiceType->name == 'MixedInvoiceVATReceipt')
            {
                $mixed = true
                ;
            }
            else { $mixed = false; }
        
        
        

        return view('invoices.create', compact(
            'business',
            'date',
            'title',
            'bType',
            'invoiceType',
            'customers',
            'availableProducts',
            'currencies',
            'selectedCurrency',
            'currencyDate',
            'VAT',
            'notes',
            'mixed'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function OldStore(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);

        if ($business != NULL) {
            if ($business->user->id == Auth::id()) {
                //return dd($request->input('invoiceDiscountType'));
                $request->validate([
                    'date' => 'bail|required|date|after:yesterday',
                    'selectedCustomer' => [
                        new CustomerValidation($business, $request->input('customerType'), $request->input('customerName'))
                    ],
                    'products' => [
                        'required',
                        new ProductsValidation($business)
                    ],
                    'selectedCurrency' => [
                        'bail',
                        new SelectCurrency
                    ],
                    'invoiceDiscountAmount' => [
                        'nullable',
                        'numeric',
                        new DiscountValidation($request->input('invoiceDiscountType'))
                    ],
                    'invoiceTotalPriceBeforeVAT' => 'numeric|nullable',
                    'VAT' => 'bail|numeric|nullable',
                    'invoiceTotalPrice' => 'bail|numeric',
                    'notes' => 'max:250',
                ]);
                
                $invoiceType = InvoiceType::find($request->route()->parameters('invoiceType')['invoiceType']);
                $invoiceTypeId = $invoiceType->id;
                if ($invoiceType->name == 'InvoiceReceipt' || $invoiceType->name == 'InvoiceVATReceipt') {
                    $request->validate([
                        'payments' => [
                            new PaymentsValidation($request->input('paymentsTotal'))
                        ]
                    ]);
                } else {
                    $paymentId = null;
                }
                
                // Start Validation for Products Sum + Discount + Vat + InvoiceTotalPrice
                $products = $request->input('products');
                $productsSum = 0;
                foreach ($products as $key => $product) {
                    $productsSum += $product['pTotalPrice'];
                }
                $invoiceDiscount = $request->input('invoiceDiscount')/100;
                $invoiceTotalPriceBeforeVAT = $request->input('invoiceTotalPriceBeforeVAT');
                $VAT = $request->input('VAT')/100;
                $invoiceTotalPrice = $request->input('invoiceTotalPrice');

                $request->validate([
                    'invoiceTotalPrice' => [
                        'numeric',
                        new InvoiceTotalPriceValidation(
                            $business,
                            $productsSum,
                            $invoiceDiscountType,
                            $invoiceDiscountAmount,
                            $invoiceTotalPriceBeforeVAT,
                            $VAT
                        )
                    ],
                ]);
                // End Validation for Products Sum + Discount + Vat + InvoiceTotalPrice
               
                // Start Validation Payment Total
                if ($invoiceType->name == 'InvoiceReceipt' || $invoiceType->name == 'InvoiceVATReceipt') {
                    $request->validate([
                        'paymentsTotal' => [
                            'required',
                            'numeric',
                            new InvoiceTotalPriceAndPaymentsVaildation($request->input('invoiceTotalPrice'))
                        ]
                    ]);

                    $paymentId = PaymentsController::store($request->input('payments'), $request->input('paymentsTotal'));
                } // End Validation Payment Tota
            } // End If Auth::user->id == business->user->id
            else { return false; }
        } // End If business NULL
        else { return false; } // If business Null

        // Start Set InvoiceStatus
        if ($invoiceType->name == 'InvoiceReceipt' ||
            $invoiceType->name == 'InvoiceVATReceipt') {
                $invoiceStatus = InvoiceStatus::where('name', 'ClosedAndPaid')->first();
        }

        if ($invoiceType->name == 'Invoice' ||
            $invoiceType->name == 'InvoiceVAT') {
                $invoiceStatus = InvoiceStatus::where('name', 'WaitingForPayment')->first();
        }

        if ($invoiceType->name == 'Draft' ||
            $invoiceType->name == 'DraftVAT') {
                $invoiceStatus = InvoiceStatus::where('name', 'Draft')->first();
        }
        // End Set InvoiceStatus

        // Start Set Customer
        if ($request->input('customerType') == 'NewCustomer') {
            if ($request->input('saveCustomer') == true) {
                $show = 'Yes';
            } else { $show = 'No'; }
            $customer = Customer::create([
                'name' => $request->input('customerName'),
                'business_id' => $business->id,
                'show' => $show
            ]);
        } else {
            $customer = Customer::find($request->input('selectedCustomer'));
        }
        $customer->createCustomerRecord();
        // End Set Customer

        $docNumber = $business->docsNumberings->first()->docsCounter($invoiceType->name)+1;

        // Start SQL STORING ExepmtDealer
        if ($business->user->plan->bType == 'ExemptDealer') {
            $invoice = Invoice::create([
                'payment_id' => $paymentId,
                'customer_record_id' => $customer->getCustomerRecord()->id,
                'business_id' => $business->id,
                'invoice_type_id' => $invoiceTypeId,
                'invoice_status_id' => $invoiceStatus->id,
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'docNumber' => $docNumber,
                'date' => $request->input('date'),
                'discount_' => ($request->input('invoiceDiscount')/100),
                'totalPriceBeforeVAT' => $request->input('invoiceTotalPrice'),
                'vat_id' => null,
                'totalPrice' => $request->input('invoiceTotalPrice'),
                'notes' => $request->input('notes'),
            ]);
        } // End SQL STORING ExepmtDealer

        // Start SQL STORING AuthorizedDealer
        if ($business->user->plan->bType == 'AuthorizedDealer') {
            if ($request->input('VATRequired') == true) {
                $invoice =Invoice::create([
                    'payment_id' => $paymentId,
                    'customer_record_id' => $customer->getCustomerRecord()->id,
                    'business_id' => $business->id,
                    'invoice_type_id' => $invoiceTypeId,
                    'invoice_status_id' => $invoiceStatus->id,
                    'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                    'docNumber' => $docNumber,
                    'date' => $request->input('date'),
                    'discount' => ($request->input('invoiceDiscount')/100),
                    'totalPriceBeforeVAT' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'vat_id' => VAT::where('endDate', null)->first()->id,
                    'totalPrice' => $request->input('invoiceTotalPrice'),
                    'notes' => $request->input('notes'),
                ]);
            } else { 
                $invoice =Invoice::create([
                    'payment_id' => $paymentId,
                    'customer_record_id' => $customer->getCustomerRecord()->id,
                    'business_id' => $business->id,
                    'invoice_type_id' => $invoiceTypeId,
                    'invoice_status_id' => $invoiceStatus->id,
                    'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                    'docNumber' => $docNumber,
                    'date' => $request->input('date'),
                    'discount' => ($request->input('invoiceDiscount')/100),
                    'totalPriceBeforeVAT' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'vat_id' => null,
                    'totalPrice' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'notes' => $request->input('notes'),
                ]);
            }

            
        } // End SQL STORING AuthorizedDealer

        // Start STORING ProductLinesInInvoice
        ProductLineInInvoicesController::store($products, $invoice->id);
        // End STORING ProductLinesInInvoice
    } // End Store Function

    public function store(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        
        $request->validate([
            'date' => 'bail|required|date|after:yesterday',
            'selectedCustomer' => [
                new CustomerValidation($business, $request->input('customerType'), $request->input('customerName'))
            ],
            'products' => [
                'required',
                new ProductsValidation($business)
            ],
            'selectedCurrency' => [
                'bail',
                new SelectCurrency
            ],
            'invoiceDiscountAmount' => [
                'nullable',
                'numeric',
                new DiscountValidation($request->input('invoiceDiscountType'))
            ],
            'notes' => 'max:250',
        ]);



        
    } // End Store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $business = business::find($request->route()->parameters('business')['business']); 
        $invoice = Invoice::find($request->route()->parameters('invoice')['invoice']);
        $title = 'הצג ' . $invoice->invoiceType->title . ' מספר ' . $invoice->id;
        $productLinesInInvocie = $invoice->productLineInInvoices;

        return view('invoices.show', compact(
            'title',
            'business',
            'invoice',
            'productLinesInInvocie'
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
        $business = Business::find($request->route()->parameters('business')['business']);
        $date = date("Y-m-d");
        $invoice = Invoice::find($request->route()->parameters('invoice')['invoice']);
        $title = 'ערוך ' . $invoice->invoiceType->title . ' מספר ' . $invoice->id;
        $currencies = Currency::all();
        $customers = Business::find($request->route()->parameters('business')['business'])->customers;
        $availableProducts = Business::find($request->route()->parameters('business')['business'])->products;
        $products = [];
        foreach ($invoice->productLineInInvoices as $key => $productLineInInvoice) {
            $products[$key] = [
                'pName' => $productLineInInvoice->product->id,
                'pQuantity' => $productLineInInvoice->quantity,
                'pQuantity' => $productLineInInvoice->quantity,
                'pPrice' => $productLineInInvoice->productPrice,
                'pTotalPrice' => $productLineInInvoice->totalPrice,
                'pType' => "ExistingCustomer",
                'pSave' => false,
            ];
        }

        // $invoiceTotalPrice = round($invoice->totalPrice, 2);

        if ($invoice->vat != null) {
            $VATRequired = true;
            $InvoiceTotalPriceBeforeVATLabel = 'מחיר לפני מע"מ';
        }
        else {
            $VATRequired = false;
            $InvoiceTotalPriceBeforeVATLabel = 'מחיר סופי לתשלום';
        }
        $VAT =  VAT::where('endDate', null)->first()->percentage;

        return view('invoices.edit', compact(
            'business',
            'date',
            'title',
            'bType',
            'invoiceType',
            'customers',
            'availableProducts',
            'currencies',
            'invoice',
            'products',
            'VATRequired',
            'InvoiceTotalPriceBeforeVATLabel',
            'VAT'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $invoice = Invoice::find($request->route()->parameters('invoice')['invoice']);
        $request->validate([
            'date' => 'bail|required|date|after:yesterday',
            'selectedCustomer' => [
                'bail',
                'required',
                new CustomerValidation($business, $request->input('customerType'), $request->input('customerName'))
            ],
            'products' => [
                'required',
                new ProductsValidation($business)
            ],
            'selectedCurrency' => [
                'bail',
                new SelectCurrency
            ],
            'invoiceDiscount' => 'bail|digits_between:0,100',
            'invoiceTotalPriceBeforeVAT' => 'numeric|nullable',
            'VAT' => 'bail|numeric|nullable',
            'invoiceTotalPrice' => 'bail|numeric',
            'notes' => 'max:250',
        ]);

        // Start Validation for Products Sum + Discount + Vat + InvoiceTotalPrice
        $products = $request->input('products');
        $productsSum = 0;
        foreach ($products as $key => $product) {
            $productsSum += $product['pTotalPrice'];
        }
        $invoiceDiscount = $request->input('invoiceDiscount')/100;
        $invoiceTotalPriceBeforeVAT = $request->input('invoiceTotalPriceBeforeVAT');
        $VAT = $request->input('VAT')/100;
        $invoiceTotalPrice = $request->input('invoiceTotalPrice');

        $request->validate([
            'invoiceTotalPrice' => [
                'numeric',
                new InvoiceTotalPriceValidation(
                    $business,
                    $productsSum,
                    $invoiceDiscount,
                    $invoiceTotalPriceBeforeVAT,
                    $VAT
                )
            ],
        ]);
        
        // Set Customer
        if ($request->input('customerType') == 'NewCustomer') {
            if ($request->input('saveCustomer') == true) {
                $show = 'Yes';
            } else { $show = 'No'; }
            $customer = Customer::create([
                'name' => $request->input('customerName'),
                'business_id' => $business->id,
                'show' => $show
            ]);
        } else {
            $customer = Customer::find($request->input('selectedCustomer'));
        }
        $customer->createCustomerRecord();
        // End Customer

        if ($invoice->invoiceType->bType == 'ExemptDealer') {
            $invoice->update([
                'customer_record_id' => $customer->getCustomerRecord()->id,
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'date' => $request->input('date'),
                'discount' => ($request->input('invoiceDiscount')/100),
                'totalPriceBeforeVAT' => $request->input('invoiceTotalPrice'),
                'vat_id' => null,
                'totalPrice' => $request->input('invoiceTotalPrice'),
                'notes' => $request->input('notes'),
            ]);
        }

        if ($invoice->invoiceType->bType == 'AuthorizedDealer') {
            if ($request->input('VATRequired') == true) {
                $invoice->update([
                    'customer_record_id' => $customer->getCustomerRecord()->id,
                    'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                    'date' => $request->input('date'),
                    'discount' => ($request->input('invoiceDiscount')/100),
                    'totalPriceBeforeVAT' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'vat_id' => VAT::where('endDate', null)->first()->id,
                    'totalPrice' => $request->input('invoiceTotalPrice'),
                    'notes' => $request->input('notes'),
                ]);
            } else {
                $invoice->update([
                    'customer_record_id' => $customer->getCustomerRecord()->id,
                    'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                    'date' => $request->input('date'),
                    'discount' => ($request->input('invoiceDiscount')/100),
                    'totalPriceBeforeVAT' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'vat_id' => null,
                    'totalPrice' => $request->input('invoiceTotalPriceBeforeVAT'),
                    'notes' => $request->input('notes'),
                ]);
            }
            
        }
        
        ProductLineInInvoicesController::update($products, $invoice);
    } // End Update Function

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice= Invoice::find($request->route()->parameters('invoice')['invoice']);
        if ($invoice->invoiceStatus->status == 'Draft') {
            foreach ($invoice->productLineInInvoices as $key => $productLineInInvoice) {
                $productLineInInvoice->delete();
            }
            $invoice->delete();
        }
        $businessId = $request->route()->parameters('business')['business'];
        return redirect('/home'); 
    }
}
