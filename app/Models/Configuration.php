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
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::firstOrCreate(
                ['id' => 1],
                [
                    'nome_banca' => 'Banca Santa Rita',
                    'descricao' => 'Seu ponto de encontro com a informação, cultura, revistas, jornais e novidades.',
                    'endereco' => 'Praça Central, s/n - Centro',
                    'telefone' => '(11) 98765-4321',
                    'whatsapp' => '11987654321',
                    'email' => 'contato@bancasantarita.com.br',
                    'instagram' => 'bancasantarita',
                    'facebook' => 'bancasantarita',
                    'horario' => 'Segunda a Sábado: 06h às 20h | Domingos e Feriados: 06h às 14h',
                    'texto_sobre' => 'A Banca Santa Rita é mais do que um ponto de venda: é um espaço de convivência, cultura e tradição na nossa comunidade. Oferecemos os principais jornais diários, revistas especializadas, lançamentos literários, gibis clássicos e colecionáveis, além de itens selecionados de papelaria e conveniência.',
                ]
            );
        });
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
}
