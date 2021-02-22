<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanUser extends Model
{
    use HasFactory;

    protected $table = 'loan_users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'users_id', 'loan_id', 'referrence_code', 'due_date', 'amount','profee', 'interest', 'rep','tranch','start','stop','terminate','penalty','rate','days','status'
    ];
}
