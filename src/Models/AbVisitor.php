<?php

namespace folez\LaravelAB\Models;

use folez\LaravelAB\Contracts\VisitorInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbVisitor extends Model implements VisitorInterface
{
    protected $fillable = [
        'visitor_id',
        'variant_id',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(AbVariant::class, 'variant_id');
    }

    public function getVariant(): AbVariant
    {
        return $this->variant;
    }

    public function setVariant(AbVariant $variant): void
    {
        $this->variant_id = $variant->id;
        $this->save();
    }

    public function hasVariant(): bool
    {
        return ! is_null($this->variant_id) && $this->variant_id;
    }
}
