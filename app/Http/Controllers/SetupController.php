<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class SetupController extends Controller
{
    public function currencies()
    {
        // Currency::CreateCurrencies();
        Currency::UpdateCurrencies();
    }
}
