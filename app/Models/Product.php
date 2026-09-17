<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
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
        'imagem',
        'preco',
        'categoria_id',
        'destaque',
        'status',
        'ordem',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'preco' => 'decimal:2',
        'ordem' => 'integer',
    ];

    /**
     * Get the category that owns the exhibition item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    /**
     * Scope a query to only include published items.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publicado');
    }

    /**
     * Scope a query to only include featured items.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('destaque', 'sim');
    }

    /**
     * Scope a query to order items by display order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordem', 'asc')->orderBy('nome', 'asc');
    }

    /**
     * Check if item is featured.
     */
    public function isFeatured(): bool
    {
        return $this->destaque === 'sim';
    }

    /**
     * Check if item is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'publicado';
    }

    /**
     * Get display image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (! empty($this->imagem)) {
            if (str_starts_with($this->imagem, 'http://') || str_starts_with($this->imagem, 'https://')) {
                return $this->imagem;
            }

            if (str_starts_with($this->imagem, 'images/')) {
                return asset($this->imagem);
            }

            return Storage::disk('public')->url($this->imagem);
        }

        return asset('images/placeholder-item.svg');
    }

    /**
     * Get formatted informative price in BRL.
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->preco !== null && $this->preco > 0) {
            return 'R$ '.number_format((float) $this->preco, 2, ',', '.');
        }

        return null;
    }
}
