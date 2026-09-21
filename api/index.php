<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Diagnóstico e tratamento de rotas para Vercel Serverless
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Se for requisição de arquivo estático existente em /public, o servidor embutido serve direto
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$publicFile = __DIR__.'/../public'.$uri;
if ($uri !== '/' && file_exists($publicFile) && ! is_dir($publicFile)) {
    return false;
}

try {
    // 1. Garantir carregamento do autoloader
    if (! file_exists(__DIR__.'/../vendor/autoload.php')) {
        throw new RuntimeException('vendor/autoload.php não foi encontrado no deploy da Vercel.');
    }
    require_once __DIR__.'/../vendor/autoload.php';

    // 2. Preparar diretórios essenciais em /tmp (único local com permissão de escrita)
    $tmpDirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/logs',
        '/tmp/storage/app/public',
    ];

    foreach ($tmpDirs as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    // 3. Inicializar aplicação Laravel
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->useStoragePath('/tmp/storage');

    $request = Request::capture();
    $app->handleRequest($request);

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Diagnóstico Vercel - Erro</title>';
    echo '<style>body{font-family:system-ui,-apple-system,sans-serif;background:#0f172a;color:#f8fafc;padding:2rem;line-height:1.6;}';
    echo '.card{background:#1e293b;border-radius:12px;padding:2rem;max-width:900px;margin:0 auto;border:1px solid #334155;}';
    echo 'h1{color:#ef4444;font-size:1.5rem;margin-top:0;} pre{background:#090d16;padding:1rem;border-radius:8px;overflow-x:auto;color:#38bdf8;font-size:0.85rem;}';
    echo 'strong{color:#94a3b8;}</style></head><body><div class="card">';
    echo '<h1>⚠️ Diagnóstico de Erro no Servidor (Vercel)</h1>';
    echo '<p><strong>Mensagem:</strong> '.htmlspecialchars($e->getMessage()).'</p>';
    echo '<p><strong>Exceção:</strong> '.get_class($e).'</p>';
    echo '<p><strong>Arquivo:</strong> '.htmlspecialchars($e->getFile()).' na linha '.$e->getLine().'</p>';
    echo '<p><strong>Stack Trace:</strong></p>';
    echo '<pre>'.htmlspecialchars($e->getTraceAsString()).'</pre>';
    echo '</div></body></html>';
    exit(1);
}
