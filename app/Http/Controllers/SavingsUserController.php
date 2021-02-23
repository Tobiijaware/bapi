<?php

namespace App\Http\Controllers;
use App\Models\Savings;
use App\Models\User;
use DB;
use App\Models\SavingsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsUserController extends Controller
{
    protected $user;

    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    public function index(){
     
        $savings = Savings::all();
        return response()->json(['message' => 'Success', 'usersavingspackages'=> $savings]);
       
    }

    public function allusersavings(){
     $id = Auth::user()->user_id;
        $response = SavingsUser::where('user_id', $id)->get();
        return response()->json(['message' => 'Success','allusersavings'=>  $response]);           
    }


    public function updatesavings(Request $request)
    {   
        $refs = $request['ref'];
        $amount = $request['amount'];
        $period = $request['tenure'];

        $newsavings = SavingsUser::where('ref',$refs)
        ->update([
            'amount'=>$amount,
             'period'=>$period
            ]);
            if($newsavings){
                return response()->json(['success' => 'Operation Successful']);       
            }
            return response()->json(['error' => 'Operation Failed'], 400);         
    }


    public function deletesavings(Request $request){
        $ref = $request['ref'];
        $id = Auth::user()->user_id;
        if(empty($ref)){
            return response()->json(['error' => 'Reference Number Cannot Be Empty'], 400);
        }
        $deletesavings = SavingsUser::where('ref',$ref) ->where('users_id', $id)->delete();
        if($deletesavings){
            return response()->json(['success' => 'Operation Successful'], 200);
        }
        return response()->json(['error' => 'Bad Request'], 400);
    }



    public function submitsavings(Request $request)
    {
         
          $ref = $this->win_hash(10);
          $amount = $request['amount'];
          $period = $request['tenure'];
          $savings_id = $request['savings_id'];
          $rep = Auth::user()->user_id;
          $sid =  $savings_id;
          $data = DB::select("SELECT * FROM savings WHERE savings_id='$sid'");
          foreach($data as $k){
           $rate = $k->rate;
           $min =$k->minimum_amount;
           $max =$k->maximum_amount;
          }
          $status = 1;
          $userid = Auth::user()->user_id;
          $check = DB::table('savingsuser')->get()->where('user_id', $userid)->where('status', 1)->count();
          if($request['amount']<$min or $request['amount']>$max)
          {
          return response()->json(['error'=> 'Invalid amount, choose between ₦'.$min.' and ₦'.$max],400);
          }
          elseif($check > 0)
            {
              return response()->json(['error'=> 'You currently have a similar savings account. Awaiting First Deposit'],400);
            }
          else{
            $insert = DB::select("INSERT INTO savingsuser (user_id,ref,savings_id,amount,period,rep,rate,status) VALUES ('$userid','$ref','$savings_id','$amount','$period','$rep','$rate','$status')");
            return response()->json(['success' => 'Operation Successful'], 200);
             
            }
         }





    protected function guard(){
        return Auth::guard();
    }
}
