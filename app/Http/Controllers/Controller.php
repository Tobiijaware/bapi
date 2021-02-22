<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function win_hash($length)
    {
        return substr(str_shuffle(str_repeat('123456789',$length)),0,$length);
    }

    public function walletbalance(){
        $id = Auth::user()->user_id;
        $balance =  DB::table('walletbalance')
                    ->where('user_id', $id)
                    ->sum('amount');
                    return $balance;
    }
}
