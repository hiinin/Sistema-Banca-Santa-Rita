<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Handle incoming visitor message and return smart contextual response.
     */
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:300'],
        ]);

        $rawMessage = trim(strip_tags((string) $validated['message']));
        if (empty($rawMessage)) {
            return response()->json([
                'reply' => 'Olá! Não consegui compreender sua mensagem. Como posso ajudar com a Banca Santa Rita hoje?',
                'items' => [],
                'suggestions' => $this->getDefaultSuggestions(),
            ]);
        }

        $config = Configuration::current();
        $normalized = Str::lower(Str::ascii($rawMessage));

        // 1. Saudações e Cumprimentos
        if ($this->matchesIntent($normalized, ['oi', 'ola', 'bom dia', 'boa tarde', 'boa noite', 'e ai', 'opa', 'alo', 'tudo bem', 'comecar'])) {
            return response()->json([
                'reply' => "Olá! Sou a **Rita**, assistente virtual da **{$config->nome_banca}**! 👋\n\nEstou aqui para te ajudar a encontrar quadrinhos, jornais, revistas, livros e itens do nosso expositor, além de informações sobre horários e localização. Em que posso te ajudar agora?",
                'items' => [],
                'suggestions' => [
                    '🕒 Horário de funcionamento',
                    '📍 Onde fica a banca?',
                    '🗞️ Jornais do dia',
                    '🦸 Gibis e Mangás',
                    '💬 Falar com atendente humano',
                ],
            ]);
        }

        // 2. Horário de Funcionamento
        if ($this->matchesIntent($normalized, ['horario', 'horas', 'abre', 'fecha', 'funcionamento', 'aberto', 'domingo', 'sabado', 'feriado', 'expediente'])) {
            $horario = $config->horario ?: 'Segunda a Sexta: das 08:00 às 18:00 | Sábados: das 09:00 às 17:00';

            return response()->json([
                'reply' => "⏰ **Nosso Horário de Funcionamento:**\n\n{$horario}\n\n• **Segunda a Sexta:** das 08:00 às 18:00\n• **Sábados:** das 09:00 às 17:00\n\nVocê também pode nos chamar no WhatsApp ou telefone para checar se algum exemplar específico ainda está disponível hoje!",
                'items' => [],
                'whatsapp_url' => $config->getWhatsappUrl('Olá! Gostaria de confirmar se a banca está aberta hoje.'),
                'suggestions' => [
                    '📍 Onde fica a banca?',
                    '📦 Como fazer uma reserva?',
                    '🔍 Ver itens em destaque',
                ],
            ]);
        }

        // 3. Endereço e Localização
        if ($this->matchesIntent($normalized, ['onde fica', 'endereco', 'localizacao', 'localizao', 'como chegar', 'mapa', 'bairro', 'rua', 'onde vcs estao', 'onde voces ficam'])) {
            $endereco = $config->endereco ?: 'Praça 7 de Setembro (Praça do Peladão), s/n - Ao lado do Hospital Bom Samaritano, Zona 05, Maringá - PR';

            return response()->json([
                'reply' => "📍 **Onde estamos localizados:**\n\n{$endereco}\n\nEstamos situados na tradicional **Praça do Peladão** em Maringá - PR, bem ao lado do **Hospital Bom Samaritano**, com fácil acesso para você retirar seus jornais, revistas, quadrinhos e colecionáveis favoritos!",
                'items' => [],
                'whatsapp_url' => $config->getWhatsappUrl('Olá! Gostaria de saber como chegar até a Banca Santa Rita.'),
                'suggestions' => [
                    '🕒 Horário de funcionamento',
                    '🗞️ Quais jornais vocês têm?',
                    '💬 Chamar no WhatsApp',
                ],
            ]);
        }

        // 4. Contato Humano / Telefone / WhatsApp
        if ($this->matchesIntent($normalized, ['humano', 'atendente', 'whatsapp', 'zap', 'telefone', 'contato', 'falar com alguem', 'pessoa real', 'atendimento'])) {
            $tel = $config->telefone ?: '(44) 9842-4758';

            return response()->json([
                'reply' => "🤝 **Fale Diretamente com Nossa Equipe!**\n\nNosso time está à sua disposição:\n• 📞 **Telefone:** {$tel}\n• 💬 **WhatsApp:** {$tel}\n\nVocê pode nos ligar diretamente ou clicar no botão abaixo para iniciar uma conversa no WhatsApp!",
                'items' => [],
                'whatsapp_url' => $config->getWhatsappUrl('Olá! Gostaria de falar com o atendente da Banca Santa Rita.'),
                'suggestions' => [
                    '🕒 Horário de funcionamento',
                    '📍 Endereço da banca',
                    '🔍 Ver catálogo digital',
                ],
            ]);
        }

        // 5. Reservas e Encomendas
        if ($this->matchesIntent($normalized, ['reservar', 'reserva', 'guardar', 'encomendar', 'encomenda', 'assinatura', 'assinar', 'como comprar', 'pedir'])) {
            return response()->json([
                'reply' => "📦 **Como Reservar seu Exemplar na Banca Santa Rita:**\n\nNosso expositor é digital e informativo. Para reservar qualquer jornal, gibi, mangá ou colecionável, basta nos enviar uma mensagem no WhatsApp com o título desejado! Nós separamos na hora para você retirar no balcão.",
                'items' => [],
                'whatsapp_url' => $config->getWhatsappUrl('Olá! Gostaria de reservar um exemplar na banca.'),
                'suggestions' => [
                    '💬 Falar com atendente no WhatsApp',
                    '🦸 Ver quadrinhos e mangás',
                    '🕒 Horários de retirada',
                ],
            ]);
        }

        // 6. Busca no Catálogo (Produtos e Categorias)
        $searchResult = $this->searchCatalog($rawMessage, $normalized, $config);
        if ($searchResult !== null) {
            return response()->json($searchResult);
        }

        // 7. Fallback inteligente
        return response()->json([
            'reply' => "Entendi sua dúvida sobre **\"{$rawMessage}\"**! Como nosso acervo físico na banca conta com centenas de jornais, gibis, livros e revistas atualizados diariamente, nosso atendente no WhatsApp pode verificar a prateleira em tempo real para você agora mesmo.",
            'items' => [],
            'whatsapp_url' => $config->getWhatsappUrl("Olá! Gostaria de saber sobre: {$rawMessage}"),
            'suggestions' => [
                '💬 Consultar no WhatsApp agora',
                '🔍 Ver catálogo completo',
                '🕒 Horário de funcionamento',
                '📍 Endereço da banca',
            ],
        ]);
    }

    /**
     * Search products or categories matching visitor keywords.
     *
     * @return array<string, mixed>|null
     */
    protected function searchCatalog(string $rawMessage, string $normalized, Configuration $config): ?array
    {
        // Palavras-chave genéricas de intenção de busca
        $searchWords = ['gibi', 'gibis', 'manga', 'mangas', 'quadrinho', 'quadrinhos', 'livro', 'livros', 'revista', 'revistas', 'jornal', 'jornais', 'colecionavel', 'colecionaveis', 'album', 'figurinha', 'tem', 'temos', 'valor', 'preco', 'catalogo'];

        $hasSearchIntent = false;
        foreach ($searchWords as $w) {
            if (str_contains($normalized, $w)) {
                $hasSearchIntent = true;
                break;
            }
        }

        // Se a mensagem for pequena (1 a 4 palavras), também tratamos como potencial busca
        $wordCount = str_word_count($normalized);
        if ($wordCount <= 4 && ! $hasSearchIntent) {
            $hasSearchIntent = true;
        }

        if (! $hasSearchIntent) {
            return null;
        }

        // Limpar termos de parada para encontrar os termos de busca reais
        $stopwords = ['voce', 'voces', 'tem', 'teria', 'gostaria', 'saber', 'qual', 'quais', 'quanto', 'custa', 'sobre', 'para', 'com', 'pelo', 'pela', 'uma', 'uns', 'umas', 'ola', 'por', 'favor', 'favor'];
        $cleanTokens = array_filter(explode(' ', $normalized), function ($token) use ($stopwords) {
            $trimmed = trim($token);

            return strlen($trimmed) >= 3 && ! in_array($trimmed, $stopwords, true);
        });

        $query = Product::published()->with('category');

        if (! empty($cleanTokens)) {
            $query->where(function ($q) use ($cleanTokens) {
                foreach ($cleanTokens as $token) {
                    $q->orWhere('nome', 'like', "%{$token}%")
                        ->orWhere('descricao', 'like', "%{$token}%")
                        ->orWhereHas('category', function ($catQuery) use ($token) {
                            $catQuery->where('nome', 'like', "%{$token}%");
                        });
                }
            });
        }

        $matchedProducts = $query->take(4)->get();

        if ($matchedProducts->isNotEmpty()) {
            $itemsData = $matchedProducts->map(function (Product $prod) use ($config) {
                return [
                    'id' => $prod->id,
                    'nome' => $prod->nome,
                    'preco' => $prod->formatted_price ?: 'Sob consulta',
                    'categoria' => $prod->category?->nome ?: 'Geral',
                    'imagem' => $prod->image_url,
                    'url' => route('site.products.show', $prod->slug),
                    'whatsapp_url' => $config->getWhatsappUrl("Olá! Vi o item '{$prod->nome}' no catálogo do site e gostaria de saber se está disponível para reserva."),
                ];
            })->all();

            $totalCount = $matchedProducts->count();

            return [
                'reply' => "Encontrei {$totalCount} item(ns) em exposição no nosso expositor que podem ser do seu interesse! ✨\n\nConfira abaixo e clique para reservar no WhatsApp ou ver detalhes:",
                'items' => $itemsData,
                'whatsapp_url' => $config->getWhatsappUrl("Olá! Gostaria de consultar a disponibilidade de: {$rawMessage}"),
                'suggestions' => [
                    '📦 Como fazer uma reserva?',
                    '🔍 Ver todo o catálogo',
                    '🕒 Horário de funcionamento',
                ],
            ];
        }

        // Se pesquisou por uma categoria geral (ex: 'jornais', 'revistas', 'mangas')
        $matchedCategory = Category::active()->where(function ($q) use ($cleanTokens) {
            foreach ($cleanTokens as $token) {
                $q->orWhere('nome', 'like', "%{$token}%");
            }
        })->first();

        if ($matchedCategory) {
            $categoryProducts = Product::published()->where('categoria_id', $matchedCategory->id)->take(3)->get();
            $itemsData = $categoryProducts->map(function (Product $prod) use ($config) {
                return [
                    'id' => $prod->id,
                    'nome' => $prod->nome,
                    'preco' => $prod->formatted_price ?: 'Sob consulta',
                    'categoria' => $prod->category?->nome ?: 'Geral',
                    'imagem' => $prod->image_url,
                    'url' => route('site.products.show', $prod->slug),
                    'whatsapp_url' => $config->getWhatsappUrl("Olá! Vi '{$prod->nome}' na categoria {$prod->category?->nome} e gostaria de reservar."),
                ];
            })->all();

            return [
                'reply' => "Temos uma categoria especial dedicada a **{$matchedCategory->nome}**! Veja alguns dos itens em exposição na banca:",
                'items' => $itemsData,
                'whatsapp_url' => $config->getWhatsappUrl("Olá! Gostaria de saber quais títulos de {$matchedCategory->nome} estão disponíveis hoje."),
                'suggestions' => [
                    '📦 Como fazer uma reserva?',
                    '🔍 Ver todo o catálogo',
                    '💬 Chamar no WhatsApp',
                ],
            ];
        }

        return null;
    }

    /**
     * Check if normalized text matches any given intent keyword.
     *
     * @param  string[]  $keywords
     */
    protected function matchesIntent(string $text, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (Str::contains($text, $kw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Default suggestion chips.
     *
     * @return string[]
     */
    protected function getDefaultSuggestions(): array
    {
        return [
            '🕒 Horários de funcionamento',
            '📍 Onde fica a banca?',
            '🗞️ Jornais e Revistas',
            '🦸 Gibis e Quadrinhos',
            '💬 Falar com atendente humano',
        ];
    }
}
