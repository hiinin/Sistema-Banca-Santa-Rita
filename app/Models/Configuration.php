<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Configuration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome_banca',
        'logo',
        'favicon',
        'descricao',
        'endereco',
        'telefone',
        'whatsapp',
        'email',
        'instagram',
        'facebook',
        'horario',
        'latitude',
        'longitude',
        'texto_sobre',
    ];

    /**
     * Cache key for banca configurations.
     */
    public const CACHE_KEY = 'banca_configurations';

    /**
     * Get or create current settings instance with caching.
     */
    public static function current(): self
    {
        $cached = Cache::get(self::CACHE_KEY);
        if ($cached instanceof self) {
            return $cached;
        }

        $config = self::firstOrCreate(
            ['id' => 1],
            [
                'nome_banca' => 'Banca Santa Rita',
                'descricao' => 'Seu ponto de encontro com a informação, cultura, revistas, jornais e novidades na Praça do Peladão em Maringá.',
                'endereco' => 'Praça 7 de Setembro (Praça do Peladão), s/n - Ao lado do Hospital Bom Samaritano, Zona 05, Maringá - PR',
                'telefone' => '(44) 9842-4758',
                'whatsapp' => '4498424758',
                'email' => 'contato@bancasantarita.com.br',
                'instagram' => 'bancasantarita',
                'facebook' => 'bancasantarita',
                'horario' => 'Segunda a Sexta: das 08:00 às 18:00 | Sábados: das 09:00 às 17:00',
                'latitude' => '-23.422934',
                'longitude' => '-51.952967',
                'texto_sobre' => 'A Banca Santa Rita é mais do que um ponto de venda: é um espaço de convivência, cultura e tradição na Praça do Peladão em Maringá (ao lado do Hospital Bom Samaritano). Oferecemos os principais jornais diários, revistas especializadas, lançamentos literários, gibis clássicos e colecionáveis, além de itens selecionados de papelaria e conveniência.',
            ]
        );

        Cache::forever(self::CACHE_KEY, $config);

        return $config;
    }

    /**
     * Clear cached configurations when saved or updated.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Get Logo URL.
     */
    public function getLogoUrlAttribute(): string
    {
        if (! empty($this->logo)) {
            if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                return $this->logo;
            }

            return Storage::disk('public')->url($this->logo);
        }

        return asset('images/logo-banca-santa-rita.svg');
    }

    /**
     * Get Favicon URL.
     */
    public function getFaviconUrlAttribute(): string
    {
        if (! empty($this->favicon)) {
            if (str_starts_with($this->favicon, 'http://') || str_starts_with($this->favicon, 'https://')) {
                return $this->favicon;
            }

            return Storage::disk('public')->url($this->favicon);
        }

        return asset('favicon.ico');
    }

    /**
     * Get cleaned numeric WhatsApp number for URL generation.
     */
    public function getCleanWhatsappAttribute(): string
    {
        return preg_replace('/\D/', '', (string) $this->whatsapp);
    }

    /**
     * Generate direct WhatsApp URL with custom inquiry message.
     */
    public function getWhatsappUrl(?string $customMessage = null): string
    {
        $phone = $this->clean_whatsapp;
        if (empty($phone)) {
            return '#';
        }

        // Add Brazil DDI 55 if missing
        if (strlen($phone) <= 11 && ! str_starts_with($phone, '55')) {
            $phone = '55'.$phone;
        }

        $msg = $customMessage ?? 'Olá! Gostaria de informações sobre itens em exposição na Banca Santa Rita.';

        return 'https://wa.me/'.$phone.'?text='.urlencode($msg);
    }

    /**
     * Get Sobre text accessor alias.
     */
    public function getSobreAttribute(): ?string
    {
        return $this->texto_sobre;
    }
}
