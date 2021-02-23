<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'savingsuser';

    protected $fillable = [
        'user_id', 'ref', 'savings_id', 'amount', 'period', 'rep', 'rate', 'status'
    ];
}
