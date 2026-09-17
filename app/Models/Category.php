<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'status',
        'ordem',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ordem' => 'integer',
    ];

    /**
     * Get the contents for the category.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'categoria_id');
    }

    /**
     * Get the products/exhibition items for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'categoria_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope a query to order categories by priority.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordem', 'asc')->orderBy('nome', 'asc');
    }
}
