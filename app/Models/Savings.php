<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'savings';

    protected $fillable = [
        'rate','savings_name','reference','minimum_amount','maximum_amount','savings_id'
        ];
}
