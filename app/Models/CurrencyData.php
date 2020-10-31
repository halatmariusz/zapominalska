<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyData extends Model
{
    protected $table = 'data_currencies';

    protected $fillable = [
        'currency_id', 'type', 'value', 'update'
    ];
}
