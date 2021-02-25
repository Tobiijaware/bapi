
<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\LoanUser;
use App\Models\InvestmentUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    protected $user;

    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }

    public function fetchuserdata(){
        $id = Auth::user()->user_id;
        $userdetails = User::where('user_id', $id)->first();
        return response()->json(['user' =>  $userdetails]);
    }

    public function usertransaction(){
        $id = Auth::user()->user_id;
        $loans = LoanUser::where('users_id', $id)->get();
        $invest = InvestmentUser::where('users_id', $id)->get();
        if(empty($loans) && empty($invest)){
            return response()->json(['success' => 'No Records found'], 200);
        }else{
            return response()->json(['loans' =>  $loans, 'invest'=> $invest]);
        }

    }
    
     public function usertransactions(){
        $id = Auth::user()->user_id;
        $loans = LoanUser::where('users_id', $id)->get();
        $invest = InvestmentUser::where('users_id', $id)->get();
        $savings = SavingsUser::where('user_id', $id)->get();
        if(empty($loans) && empty($invest) &&  empty($savings)){
            return response()->json(['success' => 'No Records found'], 200);
        }else{
            return response()->json(['loans' =>  $loans, 'invest'=> $invest, 'savings'=>$savings]);
        }

    }
    
     public function saveuserphonedata(Request $request){
        $id = Auth::user()->user_id;
        $userdata = new UserPhoneData();
        $userdata->user_id = $id;
        $userdata->user_data = $request['data'];
        $userdata->save();
        if($userdata->save()){
            return response()->json(['success' => 'Records Saved'], 200);
        }else{
            return response()->json(['error' => 'Something Went Wrong'], 400);
        }
       
    }




















    protected function guard(){
        return Auth::guard();
    }
}
