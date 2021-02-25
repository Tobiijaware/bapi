<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoneData extends Model
{
    use HasFactory;


    protected $table = 'user_phonedata';

    protected $primaryKey = 'id';

    protected $casts = [
        'user_data' => 'array',
    ];

    protected $fillable = [ 'user_id' ];



}
