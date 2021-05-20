<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('CheckBusinessAndUser');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $currencies = simplexml_load_file(urlencode('https://www.boi.org.il/currency.xml'));
        $currencies = json_encode($currencies);
        $currencies = json_decode($currencies,TRUE);
        return view('home', compact(
            'currencies',
            'business'
        ));
    }
}
