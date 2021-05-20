<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;
use App\Invoice;
use App\Receipt;
use App\InvoiceType;

class DocsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusinessAndUser')->except('index', 'create', 'store');
        
    }

    public function index(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $docs = [];
        //$docs[] = $business->receipts;
        $title = 'מסמכים של עסק ' . $business->name;
        $invoiceTypes = InvoiceType::all();
        

        foreach ($business->invoices as $key => $invoice) {
            $docs[] = $invoice;
        }
        foreach ($business->receipts as $key => $receipt) {
            $docs[] = $receipt;
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

        // foreach ($docs as $key => $doc) {
        //     $docs[$key] = $doc->date;
        // }

//        return dd($docs);



        return view('docs', compact(
            'title',
            'business',
            'docs',
            'invoiceTypes'
        ));
    } // End Index function
}
