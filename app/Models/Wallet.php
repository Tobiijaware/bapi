<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallet';

    protected $primaryKey = 'id';

    protected $fillable = [
        'trno','user_id','reference','amount','ref2','type','status','remark','ctime','mm','yy','rep'
    ];
}
