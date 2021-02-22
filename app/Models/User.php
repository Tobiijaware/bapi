<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id', 'surname', 'othername', 'active',
        'email', 'sex', 'state', 'city', 'address', 'birthday',
        'accname', 'bank', 'accountno', 'bvn', 'name', 'password', 'phone', 'photo','code','senderid','usertype','time','reset'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function getJWTIdentifier(){
        return $this->getKey();
    }


    public function getJWTCustomClaims(){
        return [];
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
