<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Business;
use App\Customer;
use App\Currency;
use App\Invoice;
use App\InvoiceType;
use App\InvoiceStatus;
use App\Receipt;

use App\Rules\SelectCurrency;
use App\Rules\CustomerValidation;
use App\Rules\ProductsValidation;
use App\Rules\InvoiceTotalPriceAndPaymentsVaildation;
use App\Rules\PaymentsValidation;
use App\Rules\OpenInvoiceValidation;

use App\Http\Controllers\PaymentsController;

class ReceiptsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusiness')->only('index', 'create', 'store', 'edit', 'update', 'distroy', 'show');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'פאנל קבלות';
        $business = Business::find($request->route()->parameters('business')['business']); 
        $receipts = $business->receipts;
        return view('receipts.index', compact(
            'title',
            'business',
            'receipts'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'צור קבלה';
        $date = date("Y-m-d");
        $business = Business::find($request->route()->parameters('business')['business']);
        $customers = Business::find($request->route()->parameters('business')['business'])->customers;
        $currencies = Currency::all();
        $selectedCurrency = Business::find($request->route()->parameters('business')['business'])->currency->name;
        $notes = Business::find($request->route()->parameters('business')['business'])->notes;
        $invoices = $business->OpenInvoices();
        
        return view('receipts.create', compact(
            'title',
            'date',
            'business',
            'customers',
            'currencies',
            'selectedCurrency',
            'notes',
            'invoices'
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
        //return dd($request->input('selectedCustomer'));
        $business = Business::find($request->route()->parameters('business')['business']);
        $request->validate([
            'date' => 'bail|required|date|after:yesterday',
            'selectedCustomer' => [
                'bail',
                'required',
                new CustomerValidation($business, $request->input('customerType'), $request->input('customerName'))
            ],
            'selectedCurrency' => [
                new SelectCurrency
            ],
            'description' => 'nullable|string|max:250',
            'notes' => 'max:250',
            'payments' => [
                new PaymentsValidation($request->input('paymentsTotal'))
            ]
        ]);

        $request->validate([
            'paymentsTotal' => [
                'required',
                'numeric',
                new InvoiceTotalPriceAndPaymentsVaildation($request->input('invoiceTotalPrice'))
            ]
        ]);

        //return dd($request->input('selectedInvoice'));
        $request->validate([
            'selectedInvoice' => [
                'nullable',
                new OpenInvoiceValidation($business, $request->input('paymentsTotal'))
            ]
        ]);

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
        // End Set Customer
        
        $paymentId = PaymentsController::store($request->input('payments'), $request->input('paymentsTotal'));
        
        if ($request->input('selectedInvoice')!= '') {
            $invoice = Invoice::find($request->input('selectedInvoice'));
            $receipt = Receipt::create([
                'payment_id' => $paymentId,
                'invoice_id' => $invoice->id,
                'customer_id' => $customer->id,
                'business_id' => $business->id,
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'totalPrice' => $request->input('invoiceTotalPrice'),
                'notes' => $request->input('notes'),
            ]);
            
            if ($invoice->invoiceType->name == 'Invoice' ||
                $invoice->invoiceType->name == 'InvoiceVAT') {
                    $invoice = Invoice::find($invoice->id);
                    if ($invoice->GetSumOfPayments() == 0) {
                        $invoiceStatus = InvoiceStatus::where('name', 'ClosedAndPaid')->first();
                        $invoice->update([
                            'invoice_status_id' => $invoiceStatus->id,
                        ]);
                    }
            }
        } else {
            $invoice = null;
            $receipt = Receipt::create([
                'payment_id' => $paymentId,
                'invoice_id' => null,
                'customer_id' => $customer->id,
                'business_id' => $business->id,
                'currency_id' => Currency::GetCurrencyIdByName($request->input('selectedCurrency')),
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'totalPrice' => $request->input('invoiceTotalPrice'),
                'notes' => $request->input('notes'),
            ]);
        }

        // return dd($invoice);
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
        $receipt = Receipt::find($request->route()->parameters('receipt')['receipt']);
        $title = 'הצג קבלה מספר ' . $receipt->id;
        return view('receipts.show', compact(
            'title',
            'business',
            'receipt'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
