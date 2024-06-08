<?php

namespace folez\LaravelAB\Models;

use Illuminate\Database\Eloquent\Model;

class AbGoal extends Model
{
    protected $fillable = [
        'name',
        'hit',
    ];

    protected $casts = [
        'hit' => 'integer',
    ];

    public function incrementHit(): void
    {
        $this->increment('hit');
    }
}
