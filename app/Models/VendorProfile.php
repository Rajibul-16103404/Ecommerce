<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'shop_name', 'description', 'is_verified', 'commission_rate'])]
class VendorProfile extends Model
{
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'commission_rate' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
