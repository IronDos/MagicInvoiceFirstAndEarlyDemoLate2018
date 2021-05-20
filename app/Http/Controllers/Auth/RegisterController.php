<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Rules\IsraeliID;
use App\Rules\PlansAndBType;
use App\Plan;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:1',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'israeliId' => ['bail', 'required', 'digits:9','unique:users' , new IsraeliID],
            'plansAndBType' => ['bail', 'required', new PlansAndBType],
            'password' => 'required|string|min:6|confirmed'
        ]);

       $planId = Plan::GetPlanId($request->input('plansAndBType'));

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'israeliId' => $request->input('israeliId'),
            'plan_id' => $planId,
            'planStartDate' => date("Y-m-d"),
            'planEndDate' => date_format(date_add(date_create(date("Y-m-d")),date_interval_create_from_date_string("1 month")),"Y-m-d"),
            'password' => Hash::make($request->input('password'))
        ]);

        return ['redirect' => '/login'];
    }
}
