<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;
use App\InvoiceType;
use App\Rules\InvoiceTypeValidation;
use App\Rules\CustomerValidation;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusiness')->only(
            'index',
            'VAT',
            'VATsortbyDate',
            'VATsortbyDate'
        );
        
    }

    // Start Function index
    public function index(Request $request)
    {
        $title = 'דוחות';
        $business = Business::find($request->route()->parameters('business')['business']);
        return view('reports.index', compact(
            'title',
            'business'
        ));
    }
    // End Function index

    // Start Function VAT
    public function VAT(Request $request)
    {
        $title = 'דוח מע"מ';
        $business = Business::find($request->route()->parameters('business')['business']);
        $startDate = date("Y-m-d", strtotime("first day of previous month"));
        $endDate = date("Y-m-d", strtotime("last day of previous month"));

        $VATReport = $business->VATReport($startDate, $endDate);
        
        


        return view('reports.vat', compact(
            'title',
            'business',
            'startDate',
            'endDate',
            'VATReport'
        )); 
    } // End Function VAT

    // Start Function VATsortbyDate
    public function VATsortbyDate(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $request->validate([
            'startDate' => 'date',
            'endDate' => 'date'
        ]);

        return $business->VATReport($request->input('startDate'), $request->input('endDate'));
    } // End Function VATsortbyDate
    

    // Start Function Income
    public function income(Request $request)
    {
        $title = 'דוח הכנסות';
        $business = Business::find($request->route()->parameters('business')['business']);
        $customers = Business::find($request->route()->parameters('business')['business'])->customers;
        $startDate = date("Y-m-d", strtotime("first day of previous month"));
        $endDate = date("Y-m-d", strtotime("last day of previous month"));
        $invoiceTypes = InvoiceType::all()->where('bType', $business->user->plan->bType)->whereNotIn('name', ['Draft', 'DraftVAT']);
        $invoiceTypeNew = [];
        foreach ($invoiceTypes as $key => $invoiceType) {
            $invoiceTypeNew[]= [
                'id' => $invoiceType->id,
                'title' => $invoiceType->title,
                'selected' => true
            ];
        }
        $invoiceTypes = $invoiceTypeNew;

        return view('reports.income', compact(
            'title',
            'business',
            'startDate',
            'endDate',
            'customers',
            'invoiceTypes'
        )); 
    } // End Function Income
    

    // Start Function Income
    public function incomeSortByDate(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $request->validate([
            'startDate' => 'date',
            'endDate' => 'date',
            'selectedCustomer' => [
                new CustomerValidation($business, 'notRequired', '')
            ],
            'invoiceTypes' => [
                'required',
                new InvoiceTypeValidation
            ]
        ]);
        
        $totalPrice = 0;
        $VAT = 0;
        $totalPriceBeforeVAT = 0;

        foreach ($business->invoices as $key => $invoice) {
            if ($invoice->invoiceType->name == 'InvoiceReceipt' ||
                $invoice->invoiceType->name == 'InvoiceVATReceipt') {
                    $totalPrice += $invoice->totalPrice;
                    $totalPriceBeforeVAT += $invoice->totalPriceBeforeVAT;
            }

            if ($invoice->invoiceType->name == 'Invoice' ||
                $invoice->invoiceType->name == 'InvoiceVAT') {
                    $VAT += $invoice->totalPrice - $invoice->totalPriceBeforeVAT;
            }
        }

        foreach ($business->receipts as $key => $receipt) {
            $totalPrice += $receipt->totalPrice;
        }

        
        return [
            'totalPrice' => $totalPrice,
            'totalPriceBeforeVAT' => $totalPriceBeforeVAT,
            'VAT' => $VAT,
            
        ];
    } // End Function Income
    
}
