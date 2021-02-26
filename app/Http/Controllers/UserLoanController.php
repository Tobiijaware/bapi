<?php

namespace App\Http\Controllers;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoanController extends Controller
{


    protected $user;

    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }


    public function index(){
     
        $loans = Loan::all();
        return response()->json(['message' => 'Success', 'userloanpackages'=> $loans]);
       
    }



    public function submit(Request $request){
        $request->validate([
            'loan_id' => 'required',
            'amount' => 'required',
            'tenure' => 'required'
        ]);

        $getloandetails = Loan::find($request['loan_id']);
        if($request->amount > $getloandetails->maximum_amount){
            return response()->json(['error' => 'Amount Entered Is Greater Than Maximum Amount'], 400);
        }elseif($request->amount < $getloandetails->minimum_amount){
            return response()->json(['error' => 'Amount Entered Is Lesser Than Minimum Amount'], 400);
        }
       

        $id =  Auth::user()->user_id;
        $loan = new LoanUser();
        $loan->users_id = $id;
        $loan->loan_id = $getloandetails->id;
        $loan->referrence_code = $this->win_hash(8);
        $loan->amount = $request['amount'];
        $loan->profee = $request['amount'] *$getloandetails->processing_rate/100;
        $time = time();
        $loan->days =  $request['tenure'];
        $tenure =  $request['tenure'];
        
         $loan->surname = $request['surname'];
        $loan->othername = $request['othername'];
        $loan->phone = $request['phone'];
        $loan->email = $request['email'];
        $loan->gender = $request['gender'];
        $loan->marital_status = $request['marital_status'];
        $loan->education = $request['education'];
        $loan->homeaddress = $request['homeaddress'];
        $loan->state = $request['state'];
        $loan->city = $request['city'];
        $loan->emp_status = $request['emp_status'];
        $loan->dob = strtotime($request['dob']);
        $loan->bvn = $request['bvn'];
        $loan->work_details = $request['work_details'];
        $loan->guarantor_name = $request['guarantor_name'];
        $loan->rel_with_guar = $request['rel_with_guar'];
        $loan->number_of_guar = $request['number_of_guar'];
        $loan->loan_purpose = $request['loan_purpose'];
        $loan->how_did_u_find = $request['how_did_u_find'];
        
        

        $interest = ($request['amount'])*($getloandetails->rate)*($request['tenure'])/100/30;
        $loan->interest = $interest;
        $loan->rate = $getloandetails->rate;
        $loan->due_date = strtotime('+'.$tenure.' days', $time);
        $loan->status = 1;
        $loan->rep = $id;
        $expected = $request['amount'] + $interest;
        $tranch = $expected * 30 / $request['tenure'];
        $loan->tranch = $tranch;
        $loan->penalty = $request['amount']*$getloandetails->penalty/100;
        $loan->save();
        return response()->json(['success'=>'Loan Application Submitted, Please Wait For Approval']);
        // $userdetails = User::all()->where('user_id', $id)->first();
        // $usercarddetails = CardDetails::all()->where('user_id', $id);
       
        // $useridcard = IdCards::all()->where('user_id', $id)->count();
        // $pendingloan = LoanUser::all()->where('users_id', $id)->where('status', '!=', 5)->count();
        
        //KYC check
        // if(empty($userdetails->bvn)){
        //     return back()->with('error', 'Add Your Bvn');
        // }elseif(empty($userdetails->bank)){
        //     return back()->with('error', 'Update Your Bank Name');
        // }elseif(empty($userdetails->accountno)){
        //     return back()->with('error', 'Update Your Account Number');
        // }elseif(empty($userdetails->accname)){
        //     return back()->with('error', 'Update Your Account Name');
        // }elseif(count($usercarddetails) < 1){
        //     return back()->with('error', 'Please Link A Debit Card');
        // }elseif($pendingloan > 0){
        //     return back()->with('error', 'You Already Have A Loan Instance');
        // }elseif($useridcard < 1){
        //     return back()->with('error', 'Add A Valid ID Card');
        // }
        // else{
           
        //     $loan->save();
        //     if($loan->save()){
        //         session()->forget('userloandata');
        //         return back()->with('success', 'Loan Application Submitted, Please Wait For Approval');
        //     }
        // }

      
      

    }
//a user can only delete a loan application if it has not been approved by the admin.
//delete
    public function clearloan(Request $request){
        $ref = $request['ref'];
        $id = Auth::user()->user_id;
        if(empty($ref)){
            return response()->json(['error' => 'Reference Number Cannot Be Empty'], 400);
        }
        $deleteloan = LoanUser::where('referrence_code',$ref) ->where('users_id', $id)->delete();
        if($deleteloan){
            return response()->json(['success' => 'Operation Successful'], 200);
        }
        return response()->json(['error' => 'Bad Request'], 400);
    }
//endofdelete

//showalluserloans
    public function showuserloans(){
        $id = Auth::user()->user_id;
        $response = LoanUser::where('users_id', $id)->get();
        if($response){
            return response()->json(['message'=>'success','alluserloans'=>  $response]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
       
    }
//showalluserloans
    public function getsingleloan(Request $request){
        $ref = $request['ref'];
        if(empty($ref)){
            return response()->json(['error' => 'Reference Number Cannot Be Empty'], 400);
        }
        $data = LoanUser::all()->where('referrence_code',$ref);
        return response()->json(['loan'=>$data]);
          
    }


    protected function guard(){
        return Auth::guard();
    }
}
