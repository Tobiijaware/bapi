<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentUser extends Model
{
    use HasFactory;

    protected $table = 'investment_users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'users_id', 'investment_id', 'referrence_code', 'type', 'due_date', 'amount', 'status', 'rep','interest','tenure','start'
    ];
}
