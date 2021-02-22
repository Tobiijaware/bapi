<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'loans';

    protected $fillable = [
    'rate','loan_name','penalty','reference','type','collateral','minimum_amount','maximum_amount',
    'user_id','processing_rate'
    ];
}
