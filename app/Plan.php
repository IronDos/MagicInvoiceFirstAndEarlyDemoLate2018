<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    public $primaryKey = 'id';

    protected $fillable = [
        'name', 'bType', 'description', 'annualPrice',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    /* 
        Used in RegisterController@Register($REQUEST)
        1. Getting array values from POST vue
        2. Validate using custom rule
        3. The data need to be insert by using the id of the plan that the user picked up in the Form
        - The  function also make validation to the $value
        - Value Stracture:
            value[0] = 'bType' of the business
            value[1] = plan name / plan type of 'bType' = ExemptDealer 
            value[2] = plan name / plan type of 'bType' = AuthorizedDealer 
        - usually only one of the  value[1] and value[2] has selected value but
        it can handle even if there is 2 values because its take only by bType
    */
    public static function GetPlanId($value)
    {
        if ($value[0] == "ExemptDealer") {
            if ($value[1] == "DocsOnly") {
                return Plan::where('bType', 'ExemptDealer')->where('name', 'DocsOnly')->first()->id;;
            } 
            if ($value[1] == "DocsAndReports") {
                return static::where('bType', 'ExemptDealer')->where('name', 'DocsAndReports')->first()->id;
            }
        }

        if ($value[0] == "AuthorizedDealer") {
            if ($value[2] == "DocsOnly") {
                return static::where('bType', 'AuthorizedDealer')->where('name', 'DocsOnly')->first()->id;
            }
            if ($value[2] == "DocsAndReports") {
                return static::where('bType', 'AuthorizedDealer')->where('name', 'DocsAndReports')->first()->id;
            }
            if ($value[2] == "DocReportsAndRepresentation") {
                return static::where('bType', 'AuthorizedDealer')->where('name', 'DocReportsAndRepresentation')->first()->id;
            }
        }
        return NULL; 
    }
}
