<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\ProductLineInInvoice;
use App\Product;
use App\ProductRecord;

class ProductLineInInvoicesController extends Controller
{
    public static function store($products, $invoiceId)
    {
        
        foreach ($products as $key => $product) {
            if ($product['pType'] == 'NewCustomer') {
                if ($product['pSave'] == true) {
                    $pSave = 'Yes';
                } else { $pSave = 'No';}
                $newProduct = Product::create([
                    'business_id' => Invoice::find($invoiceId)->business->id,
                    'name' => $product['newProductName'],
                    'show' => $pSave
                ]);
                $newProduct->createProductRecord();
                $product['pName'] = $newProduct->getProductRecord()->id;
            }
            $invoice = Invoice::find($invoiceId);
            if ($invoice->invoiceType->name == 'MixedDraft' ||
                $invoice->invoiceType->name == 'MixedInvoice' ||
                $invoice->invoiceType->name == 'MixedInvoiceReceipt')
                {
                    ProductLineInInvoice::create([
                        'product_record_id' => $product['pName'],
                        'invoice_id' => $invoiceId,
                        'productPrice' => $product['pPrice'],
                        'quantity' => $product['pQuantity'],
                        'totalPrice' => $product['pTotalPrice'],
                        'VATRequired' => $product['pVATRequired']
                    ]);                
                } else {
                    ProductLineInInvoice::create([
                        'product_record_id' => $product['pName'],
                        'invoice_id' => $invoiceId,
                        'productPrice' => $product['pPrice'],
                        'quantity' => $product['pQuantity'],
                        'totalPrice' => $product['pTotalPrice'],
                        'VATRequired' => false
                    ]);
                }
            
            if (ProductRecord::find($product['pName'])->quantity!= null) {
                if ($invoice->invoiceType->name == 'Invoice' ||
                    $invoice->invoiceType->name == 'InvoiceVAT' ||
                    $invoice->invoiceType->name == 'InvoiceReceipt' ||
                    $invoice->invoiceType->name == 'InvoiceVATReceipt') {

                        $tempProduct = ProductRecord::find($product['pName'])->product;
                        $tempProduct->update([
                            'quantity' => $tempProduct->quantity - $product['pQuantity'],
                        ]);
                        $tempProduct->createProductRecord();
                }
            }
        }
    } // End Store

    public static function update($products, $invoice)
    {
        $productLineInInvoices = $invoice->productLineInInvoices;
        $productsToStore = [];
        $productsToDelete = [];
        if (count($products)>count($productLineInInvoices)) {
            $counter = count($products);
        } else {
            $counter = count($productLineInInvoices);
        }

        for ($i=0; $i < $counter; $i++) { 
            if (isset($productLineInInvoices[$i]) &&
                isset($products[$i])) {
                $product = $products[$i];
                if ($product['pType'] == 'NewCustomer') {
                    if ($product['pSave'] == true) {
                        $pSave = 'Yes';
                    } else { $pSave = 'No';}
                    $newProduct = Product::create([
                        'business_id' => $invoice->business->id,
                        'name' => $product['newProductName'],
                        'show' => $pSave
                    ]);
                    $newProduct->createProductRecord();
                    $product['pName'] = $newProduct->getProductRecord()->id;
                }
                
                if ($invoice->invoiceType->name == 'MixedDraft' ||
                $invoice->invoiceType->name == 'MixedInvoice' ||
                $invoice->invoiceType->name == 'MixedInvoiceReceipt')
                {
                    $productLineInInvoices[$i]->update([
                        'product_record_id' => $products[$i]['pName'],
                        'invoice_id' => $invoice->id,
                        'productPrice' => $products[$i]['pPrice'],
                        'quantity' => $products[$i]['pQuantity'],
                        'totalPrice' => $products[$i]['pTotalPrice'],
                        'VATRequired' => $products['pVATRequired']
                    ]);                
                } else {
                    $productLineInInvoices[$i]->update([
                        'product_record_id' => $products[$i]['pName'],
                        'invoice_id' => $invoice->id,
                        'productPrice' => $products[$i]['pPrice'],
                        'quantity' => $products[$i]['pQuantity'],
                        'totalPrice' => $products[$i]['pTotalPrice'],
                        'VATRequired' => false
                    ]);
                }

            } elseif (isset($productLineInInvoices[$i]) &&
                        !isset($products[$i])) {
                            $productsToDelete[$i] = $productLineInInvoices[$i];
            } else {
                $productsToStore[$i] = $products[$i];
            }
        }


        if ($productsToStore != []) {
            ProductLineInInvoicesController::store($productsToStore, $invoice->id);
        }
        if ($productsToDelete != []) {
            ProductLineInInvoicesController::destroy($productsToDelete);
        }
        
    } // End Update Function

    public static function destroy($products)
    {
        // product = ProductLineInInvoice model
        // return dd($products);
        foreach ($products as $key => $product) {
            $product->delete();
        }
    } // End Destroy Function
} // End Controller
