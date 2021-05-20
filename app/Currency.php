<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'symbol',
        'unit',
        'country',
        'rate',
        'change',
        'description',
    ];

    public function business()
    {
        return $this->hasMany('App\Business');
    }

    public static function GetCurrencyIdByName($value)
    {
        if ($value == 'ILS' || $value == 'USD' || $value == 'EUR' || $value == 'GBP' || $value == 'JPY')
        {
            return static::where('name', $value)->first()->id;
        }

        return NULL;
    }

    public static function CreateCurrencies()
    {
        $url = 'https://www.boi.org.il/currency.xml';
        $xml = simplexml_load_file($url);
        $currenciesDescriptions = [
            'ILS' => 'שקל ישראלי חדש INS',
            'USD' => 'דולר ארה"ב',
            'GBP' => 'לישט',
            'JPY' => 'יין',
            'EUR' => 'אירו',
            'AUD' => 'דולר אוסטרלי',
            'CAD' => 'דולר קנדי',
            'DKK' => 'כתר דנמרק',
            'NOK' => 'כתר נורווגיה',
            'ZAR' => 'רנד',
            'SEK' => 'כתר שוודיה',
            'CHF' => 'פרנק',
            'JOD' => 'דינר ירדן',
            'LBP' => 'לירה לבנון',
            'EGP' => 'לירה מצרים',
        ];

        $currenciesSymbols = [
            'ILS' => '₪',
            'USD' => '$',
            'GBP' => '£',
            'JPY' => '¥',
            'EUR' => '€',
            'AUD' => '$',
            'CAD' => '$',
            'DKK' => 'kr.',
            'NOK' => 'kr',
            'ZAR' => 'R',
            'SEK' => 'kr',
            'CHF' => 'Fr.',
            'JOD' => 'JOD',
            'LBP' => 'LL',
            'EGP' => 'E£',
        ];

        Currency::create([
            'name' => 'ILS',
            'unit' => 1,
            'country' => 'Israel',
            'rate' => 1,
            'change' => 1,
            'description' => $currenciesDescriptions['ILS']
        ]);

        foreach ($xml as $key => $currency) {
            if(isset($currency->NAME)) {
                Currency::create([
                    'name' => $currency->CURRENCYCODE,
                    'unit' => $currency->UNIT,
                    'country' => $currency->COUNTRY,
                    'rate' => $currency->RATE,
                    'change' => $currency->CHANGE,
                    'description' => $currenciesDescriptions[(string)$currency->CURRENCYCODE]
                ]);
            }
        }
    }

    public static function UpdateCurrencies()
    {
        $currencies = Currency::all();
        if ($currencies->count() == 0) {
            Currency::CreateCurrencies();
        } else {
            $url = 'https://www.boi.org.il/currency.xml';
            $xml = simplexml_load_file($url);
            //return date('Y-m-d', strtotime((string)$xml->LAST_UPDATE));
            //return $currencies->first()->updated_at;

            if (date('Y-m-d', strtotime((string)$xml->LAST_UPDATE)) >= date('Y-m-d', strtotime((string)$currencies->first()->updated_at)))
            {
                foreach ($xml as $key => $currencyXML) {
                    if (isset($currencyXML->NAME)) {
                        $currency = Currency::where('name', $currencyXML->CURRENCYCODE);
                        
                        $currency->update([
                            'rate' => $currencyXML->RATE,
                            'change' => $currencyXML->CHANGE,
                        ]);
                    }
                }

                $currency = Currency::where('name', 'ILS');
                $currency->update([]);

            }
        }
    }
    
    // Date = '2018-12-24' (string)
    public static function GetCurrenciesByDate($dateString)
    {
        $date = date_create($dateString);
        $url = 'https://www.boi.org.il/currency.xml?rdate=' . date_format($date, 'Ymd');
        $xml = simplexml_load_file($url);
        $currencies = Currency::all();
        $newCurrencies = [];

        foreach ($xml as $key => $currencyXML) {
            if (isset($currencyXML->NAME)) {
                foreach ($currencies as $key => $currency) {
                    if ($currency->name == $currencyXML->CURRENCYCODE) {
                        $newCurrencies[] = [
                            'id' => $currency->id,
                            'name' => $currency->name,
                            'rate' => (float)$currencyXML->RATE,
                            'unit' => (float)$currencyXML->UNIT
                        ];
                        // dd($currency->rate);
                    }
                }
            }
        }

        return $newCurrencies;
    }
}
