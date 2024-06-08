<?php

namespace FoLez\LaravelAB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbVariant extends Model
{
    protected $fillable = [
        'name',
        'visitors',
    ];

    public function goals(): HasMany
    {
        return $this->hasMany(AbGoal::class, 'variant_id');
    }

    public function incrementVisitor(): void
    {
        $this->increment('visitors');
    }
}
