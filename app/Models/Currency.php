<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name', 'qty', 'code', 'buy', 'sale'
    ];

    public function details()
    {
        return $this->hasMany(CurrencyData::class, 'currency_id')->where('type', '=', 'buy');
    }
}
