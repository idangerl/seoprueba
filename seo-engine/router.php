<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

require_once __DIR__ . '/helpers.php';

$base = seo_load_base();
$map  = seo_load_map();

$slug1 = trim((string)($_GET['slug1'] ?? ''));
$slug2 = trim((string)($_GET['slug2'] ?? ''));

$renderLegacyService = static function (string $serviceSlug): void {
    $legacyFile = dirname(__DIR__) . '/servicio.php';
    if (!is_file($legacyFile)) {
        seo_404();
    }

    $_GET['servicio'] = $serviceSlug;
    require $legacyFile;
    exit;
};

if ($slug1 === '' || !isset($map[$slug1])) {
    if ($slug2 === '') {
        // Fallback al sistema legacy SOLO si el archivo existe (sitios PHP heredados)
        $renderLegacyService($slug1);
    }
    seo_404();
}

$route = [
    'slug1'    => $slug1,
    'title1'   => $map[$slug1]['title'],
    'slug2'    => null,
    'title2'   => null,
    'level'    => 1,
    'children' => $map[$slug1]['children']
];

if ($slug2 !== '') {
    if (!isset($map[$slug1]['children'][$slug2])) {
        seo_404();
    }

    $route['slug2']  = $slug2;
    $route['title2'] = $map[$slug1]['children'][$slug2]['title'];
    $route['level']  = 2;
}

if ($route['level'] === 1) {
    if (!is_file(seo_get_page_file(1, $slug1))) {
        $renderLegacyService($slug1);
    }
    require __DIR__ . '/nivel1.php';
    exit;
}

require __DIR__ . '/nivel2.php';
