<?php

namespace App\Http\Controllers;
use App\Models\Investment;
use App\Models\Wallet;
use App\Models\WalletBalance;
use App\Models\User;
use App\Models\InvestmentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInvestmentController extends Controller
{
    protected $user;

    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    public function index(){
        $investment = Investment::all();
        return response()->json(['message' => 'Success', 'userinvestmentpackages'=> $investment]);
    }


    public function submitinvestment(Request $request){
        $request->validate([
            'investment_id' => 'required',
            'amount' => 'required',
            'tenure' => 'required'
        ]);
        $getinvestmentdetails = Investment::find($request['investment_id']);
        if($request->amount > $getinvestmentdetails->maximum_amount){
            return response()->json(['error' => 'Amount Entered Is Greater Than Maximum Amount'], 400);
        }elseif($request->amount < $getinvestmentdetails->minimum_amount){
            return response()->json(['error' => 'Amount Entered Is Lesser Than Minimum Amount'], 400);
        }
        
        $investment = new InvestmentUser();
        $id = Auth::user()->user_id;
       
        $investment->users_id = $id;
        $investment->referrence_code = $this->win_hash(10);
        $investment->amount = $request['amount'];
        if($request['tenure']==10){
          $investment->tenure  = 90;
      }elseif($request['tenure']==12){
          $investment->tenure  = 180;
      }elseif($request['tenure']==15){
          $investment->tenure  = 270;
      }elseif($request['tenure']==20){
          $investment->tenure  = 360;
      }
      $investment->type = 1;
      $investment->investment_id = $getinvestmentdetails->id;
      $investment->due_date = 0;
      $investment->status = 1;
      $investment->interest = $request['amount']*$request['tenure']/100;
        $investment->rep = $id;
        $investment->save();
        if($investment->save()){
            return response()->json(['success'=>'Investment Application Submitted, Please Wait For Approval']);
        }else{
            return response()->json(['error' => 'Operation Failed'], 400);
        }
       
    }  


    public function showuserinvestment(){
        $id = Auth::user()->user_id;
        $alluserinvestment = InvestmentUser::where('users_id', $id)->get();
        if($alluserinvestment){
            return response()->json(['message'=>'success','alluserinvestment'=>  $alluserinvestment]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
        
    }

    public function deleteinvestment(Request $request){
        $ref = $request['ref'];
        $id = Auth::user()->user_id;
        if(empty($ref)){
            return response()->json(['error' => 'Reference Number Cannot Be Empty'], 400);
        }
        $deleteinv = InvestmentUser::where('referrence_code',$ref) ->where('users_id', $id)->delete();
        if($deleteinv){
            return response()->json(['success' => 'Operation Successful'], 200);
        }
        return response()->json(['error' => 'Bad Request'], 400);
    }


    public function updateinvestment(Request $request)
    {  
      $id = Auth::user()->user_id;
        $refs = $request['ref'];
        $update = InvestmentUser::where('users_id', $id)
        ->where('referrence_code', $refs)
        ->update([
            'amount' => $request->input('amount'),
            'tenure' => $request->input('tenure'),
        ]);
        if($update){
        return response()->json(['success' => 'Operation Successful'], 200);  
        }else{
        return response()->json(['error' => 'Bad Request'], 400);
        }    
    }

    public function Investmentfromwallet(Request $request){
        $request->validate([
            'investment_id' => 'required',
            'amount' => 'required',
            'tenure' => 'required'
        ]);
        $getinvestmentdetails = Investment::find($request['investment_id']);
        if($request->amount > $getinvestmentdetails->maximum_amount){
            return response()->json(['error' => 'Amount Entered Is Greater Than Maximum Amount'], 400);
        }elseif($request->amount < $getinvestmentdetails->minimum_amount){
            return response()->json(['error' => 'Amount Entered Is Lesser Than Minimum Amount'], 400);
        }
        elseif($request['amount'] > $this->walletbalance()){
         return response()->json(['error' => 'Insufficient Balance, Please Fund Wallet'], 400);
        }
        $investment = new InvestmentUser();
        $id = Auth::user()->user_id;
       
        $investment->users_id = $id;
        $investment->referrence_code = $this->win_hash(10);
        $investment->amount = $request['amount'];
        if($request['tenure']==10){
          $investment->tenure  = 90;
      }elseif($request['tenure']==12){
          $investment->tenure  = 180;
      }elseif($request['tenure']==15){
          $investment->tenure  = 270;
      }elseif($request['tenure']==20){
          $investment->tenure  = 360;
      }
        
        $investment->type = 1;
        $investment->investment_id = $getinvestmentdetails->id;
        $investment->due_date = 0;
        $investment->status = 3;
        $start = time();
        $investment->start = $start;
        $investment->due_date = $start+60*60*24*$investment->tenure;
        $investment->stop = $start+60*60*24*$investment->tenure;
      $investment->interest = $request['amount']*$request['tenure']/100;
        $investment->rep = $id;
        $investment->save();
        if($investment->save()){
            $wallet = new Wallet();
            $wallet->trno = $this->win_hash(9);
            $wallet->user_id =  Auth::user()->user_id;
            $wallet->reference = $investment->referrence_code;
            $wallet->amount = $request['amount'];
            $wallet->ref2 =  $this->win_hash(9);
            $wallet->type = 18;
            $wallet->status = 5;
            $wallet->remark = 'Investment Deposit';
            $wallet->ctime = time();
            $wallet->mm = date('m',$wallet->ctime);
            $wallet->yy = date('y',$wallet->ctime);
            $wallet->rep = Auth::user()->user_id;
            $rep = $wallet->rep;
            $investmentref =  $wallet->reference;
            $start = $wallet->ctime;
            $stop = $start+60*60*24*invName($wallet->reference,'tenure');
            $mm = date('ym', time());
            $wallet->save();
            if($wallet->save()){
              $type = 1;
              $amount = $request['amount'];
              $walletbalance = new WalletBalance();
              $walletbalance->user_id = Auth::user()->user_id;
              $walletbalance->reference = $investment->referrence_code;
              $amt = ($type>10) ? $amount : '-'.$amount;
              $walletbalance->amount = $amt;
              $walletbalance->ctime = time();
              $walletbalance->rep = Auth::user()->user_id;
              $walletbalance->save();
              return response()->json(['success' => 'Operation Successful'], 200);  
            }else{
                return response()->json(['error' => 'Bad Request'], 400);
            }
        }else{
            return response()->json(['error' => 'Bad Request'], 400);
        }


    }





    protected function guard(){
        return Auth::guard();
    }

}
