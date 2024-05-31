<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];
    public function customer() : HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function postOrder() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
