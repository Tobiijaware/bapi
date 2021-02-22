<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except'=>['login','register']]);
   }

   public function login(Request $request){
       $validator = Validator::make($request->all(),
       [
           'email' => 'required|email',
           'password' => 'required|string|min:6',
       ]);

       if($validator->fails()){
           return response()->json([$validator->errors()], 400);
       }

       $token_validity = 24*60;
       $this->guard()->factory()->setTTL($token_validity);

       if(!$token = $this->guard()->attempt($validator->validated())){
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $user = User::where('email',$request->email)->first();
       return $this->respondWithToken($token,$user);
   }

   public function register(Request $request){
       $validator = Validator::make($request->all(),[
        'surname' => ['required', 'string'],
           'othername' => ['required', 'string'],
           'email' => ['required', 'unique:users'],
        'password' => ['required', 'string', 'min:7', 'max:10'],
        'confirmpassword' => ['same:password'],
       ]);

       if($validator->fails()){
           return response()->json([$validator->errors()], 422);
       }
       $user = new User();
       $user->user_id = $this->win_hash(8);
       $user->surname = ucfirst($request['surname']);
       $user->othername = ucfirst($request['othername']);
       $user->email = $request['email'];
       $user->phone = $request['phone'];
       $user->password = bcrypt($request['password']);
       $user->save();
       if($user->save()){
        return response()->json(['message' => 'user Created Successfully', 'user'=> $user]);
       }
     
   }

//    public function profile(){
//        return response()->json($this->guard()->user());
//    }

   public function refresh(){
       return $this->respondWithToken($this->guard()->refresh());
   }

   public function logout(){
       $this->guard()->logout();
       return response()->json(['message' => 'Logout Successful']);

   }



   protected function guard(){
       return Auth::guard();
   }

   protected function respondWithToken($token,$user){
       return response()->json([
           'token' => $token,
           'token_type' => 'bearer',
           'token_validity' => $this->guard()->factory()->getTTL()*60,
           'user'=> $user
       ]);
   }
}
