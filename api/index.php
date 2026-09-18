<?php

/**
 * Ponto de entrada do Laravel para ambiente Serverless na Vercel.
 *
 * Como o sistema de arquivos na Vercel é somente leitura (read-only),
 * preparamos os diretórios temporários necessários dentro de /tmp.
 */
$tmpStorageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($tmpStorageDirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Inicializa a aplicação através do entrypoint público padrão
require __DIR__.'/../public/index.php';
