<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'roi', 'investment_name',
        'reference', 'minimum_amount', 'maximum_amount','processing_rate','collateral',
        'type','tenure'
    ];
}
