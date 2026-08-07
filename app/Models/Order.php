<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'quantity',
        'unit_price',
        'total',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'total' => 'integer',
        ];
    }
}
