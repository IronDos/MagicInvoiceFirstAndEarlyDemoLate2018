<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\PaymentsValidation;

use Validator;

use App\Currency;
use App\VAT;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'אינדקס בדיקות';
        $vat = VAT::where('endDate', null)->first()->id;
        return view('tests.index', compact('title', 'vat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'צור בדיקה';
        
        $currencies = Currency::GetCurrenciesByDate(date_create('2018-12-24'));
        return dd($currencies);
        return view('tests.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = '2018-11-26';
        $validator = Validator::make(
            ['date' => $date],
            ['date' => 'date|after:yesterday']

        );
        if ($validator->fails()) {
            echo 'תאריך לא תקין';
        } else {
            echo 'תאריך תקין';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
