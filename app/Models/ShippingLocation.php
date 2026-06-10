<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'fee'])]
class ShippingLocation extends Model
{
    protected function casts(): array
    {
        return [
            'fee' => 'decimal:2',
        ];
    }
}
