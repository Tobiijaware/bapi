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
        'users_id', 'loan_id', 'referrence_code', 'due_date', 'amount','profee', 'interest',
        'rep','tranch','start','stop','terminate','penalty','rate','days','status','surname','othername','phone','email','gender',
        'marital_status','education','homeaddress','state', 'city', 'emp_status', 'dob', 'bvn',
        'work_details','guarantor_name','rel_with_guar','number_of_guar', 'loan_purpose', 'how_did_u_find'
    ];
}
