<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'v1'
    ]
    , function ($router) {
        //Auth
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('profile', 'AuthController@profile');

});


Route::group(['middleware' => 'api',
'namespace' => 'App\Http\Controllers'], 
    function ($router) {
         //Loan Routes
        Route::get('/getloans', 'UserLoanController@index');
        Route::post('/usersubmitloan', 'UserLoanController@submit');
        Route::get('/getuserloans', 'UserLoanController@showuserloans');
        Route::post('/getsingleloan', 'UserLoanController@getsingleloan');
        Route::post('/deletesingleloan', 'UserLoanController@clearloan');

        //Investment Routes
        Route::get('/getinvestment', 'UserInvestmentController@index');
        Route::post('/usersubmitinvestment', 'UserInvestmentController@submitinvestment');
        Route::post('/deletesingleinv', 'UserInvestmentController@deleteinvestment');
        Route::post('/updateinv', 'UserInvestmentController@updateinvestment');
        Route::get('/getuserinvestment', 'UserInvestmentController@showuserinvestment');
        Route::post('/investfromwallet', 'UserInvestmentController@Investmentfromwallet');

        //Savings Routes
        Route::get('/getsavings', 'SavingsUserController@index');
        Route::get('/getusersavings', 'SavingsUserController@allusersavings');
        Route::post('/deleteusersavings', 'SavingsUserController@deletesavings');
        Route::post('/updatesavings', 'SavingsUserController@updatesavings');
        Route::post('/usersubmitsavings', 'SavingsUserController@submitsavings');
        
          //GetUserDetails
        Route::get('/userdetails', 'UserDetailsController@fetchuserdata');

        //getbothloans&investment
        Route::get('/getusertransaction', 'UserDetailsController@usertransaction');
        
          //getbothloans&investment$savings
        Route::get('/getusertransactions', 'UserDetailsController@usertransactions');
        
         //save user phone data
        Route::post('/savephonedata', 'UserDetailsController@saveuserphonedata');
        
        
        
        
});
