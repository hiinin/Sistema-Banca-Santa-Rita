<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'slug',
        'descricao',
        'tipo',
        'categoria_id',
        'imagem',
        'video_url',
        'video_arquivo',
        'ordem',
        'status',
        'destaque',
        'data_publicacao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_publicacao' => 'datetime',
        'ordem' => 'integer',
    ];

    /**
     * Get the category that owns the content.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    /**
     * Scope a query to only include published contents.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publicado');
    }

    /**
     * Scope a query to only include featured contents.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('destaque', 'sim');
    }

    /**
     * Scope a query to only include photos.
     */
    public function scopePhotos(Builder $query): Builder
    {
        return $query->where('tipo', 'foto');
    }

    /**
     * Scope a query to only include videos.
     */
    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('tipo', 'video');
    }

    /**
     * Scope a query to order contents by order and publication date.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordem', 'asc')->orderByDesc('data_publicacao')->orderByDesc('id');
    }

    /**
     * Check if content is a photo.
     */
    public function isPhoto(): bool
    {
        return $this->tipo === 'foto';
    }

    /**
     * Check if content is a video.
     */
    public function isVideo(): bool
    {
        return $this->tipo === 'video';
    }

    /**
     * Check if content is featured.
     */
    public function isFeatured(): bool
    {
        return $this->destaque === 'sim';
    }

    /**
     * Check if content is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'publicado';
    }

    /**
     * Get the display image URL (storage or default).
     */
    public function getImageUrlAttribute(): ?string
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

        // Se for vídeo com URL do YouTube e não tiver imagem própria, tenta pegar thumbnail do YouTube
        if ($this->isVideo() && ! empty($this->video_url)) {
            $youtubeId = $this->extractYoutubeId($this->video_url);
            if ($youtubeId) {
                return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
        }

        return asset('images/placeholder-content.svg');
    }

    /**
     * Get the video file URL if uploaded.
     */
    public function getVideoFileUrlAttribute(): ?string
    {
        if (! empty($this->video_arquivo)) {
            return Storage::disk('public')->url($this->video_arquivo);
        }

        return null;
    }

    /**
     * Get embeddable URL for YouTube or Vimeo.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = $this->video_url;

        // YouTube
        $youtubeId = $this->extractYoutubeId($url);
        if ($youtubeId) {
            return "https://www.youtube-nocookie.com/embed/{$youtubeId}?enablejsapi=1";
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}";
        }

        return $url;
    }

    /**
     * Extract YouTube ID from various URL formats.
     */
    protected function extractYoutubeId(string $url): ?string
    {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
