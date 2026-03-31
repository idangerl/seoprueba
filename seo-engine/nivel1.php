<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/schema-builder.php';

$_legacyFunciones = dirname(__DIR__) . '/componentes/funciones.php';
if (is_file($_legacyFunciones)) {
    require_once $_legacyFunciones;
}

$rawPage = seo_load_page(1, $route['slug1']);
if (empty($rawPage)) {
    seo_404();
}

$page = seo_resolve_page_data($rawPage, $base, $route);

$siteUrl = seo_site_url($base);
$siteRootHref = seo_base_href($base);
$canonical = seo_build_url($siteUrl, $route['slug1']);
$pageTitle = $page['seo']['title'] ?? ($route['title1'] . ' | ' . ($base['informacion_general']['nombre'] ?? ''));
$pageDescription = $page['seo']['meta_description'] ?? ($base['informacion_general']['descripcion'] ?? '');
$pageCanonical = $canonical;
$pageRobots = $page['robots'] ?? ($rawPage['robots'] ?? 'index,follow');
$pageImageAsset = seo_resolve_existing_asset($base, [
    'seo-engine/assets/images/' . $route['slug1'] . '.webp',
]);
$heroImage = (string)($pageImageAsset['relative_url'] ?? '');
$pageOgImage = (string)($pageImageAsset['absolute_url'] ?? '');

$breadcrumbsSchema = [
    ['name' => 'Inicio', 'url' => $siteUrl . '/'],
    ['name' => $route['title1'], 'url' => $canonical],
];
$breadcrumbs = [
    ['name' => 'Inicio', 'url' => $siteRootHref],
    ['name' => $route['title1'], 'url' => seo_build_href($base, $route['slug1'])],
];

$schemas = [
    seo_schema_organization($base, $siteUrl),
    seo_schema_breadcrumbs($breadcrumbsSchema),
];

if (!empty($page['schema']['json_ld'])) {
    $schemas[] = $page['schema']['json_ld'];
} else {
    $schemas[] = seo_schema_service($base, $page, $canonical);
}

$faqs = $page['faqs'] ?? [];
if (!empty($faqs)) {
    $faqNormalized = array_map(static fn($faq) => [
        'question' => $faq['pregunta'] ?? $faq['question'] ?? '',
        'answer' => $faq['respuesta'] ?? $faq['answer'] ?? '',
    ], $faqs);
    $schemas[] = seo_schema_faq($faqNormalized);
}

$hubChildren = [];
foreach ($route['children'] as $child) {
    $childSlug = $child['slug'];
    $childRawPage = seo_load_page(2, $route['slug1'], $childSlug);
    $childPage = !empty($childRawPage)
        ? seo_resolve_page_data($childRawPage, $base, [
            'slug1' => $route['slug1'],
            'title1' => $route['title1'],
            'slug2' => $childSlug,
            'title2' => $child['title'],
            'level' => 2,
            'children' => $route['children'],
        ])
        : [];

    $hubChildren[] = [
        'title' => $child['title'],
        'url' => seo_build_href($base, $route['slug1'], $childSlug),
        'image' => (string)(seo_resolve_existing_asset($base, [
            'seo-engine/assets/images/' . $route['slug1'] . '/' . $childSlug . '.webp',
        ])['relative_url'] ?? ''),
        'extracto' => (string)($childPage['seccion_seo']['introduccion'] ?? ''),
    ];
}

$fullMap = seo_load_map();
$mapKeys = array_keys($fullMap);
$currentIdx = array_search($route['slug1'], $mapKeys, true);
$siblingTopics = [];
if ($currentIdx !== false) {
    for ($i = 1; $i <= 2; $i++) {
        $nextKey = $mapKeys[$currentIdx + $i] ?? null;
        if ($nextKey !== null) {
            $siblingTopics[] = (string)($fullMap[$nextKey]['title'] ?? $nextKey);
        }
    }
}

$relatedNivelKws = [];
$relatedNivelLinks = [];
foreach ($fullMap as $slug => $entry) {
    if ($slug === $route['slug1']) {
        continue;
    }

    $title = (string)($entry['title'] ?? '');
    if ($title === '') {
        continue;
    }

    if (is_file(seo_get_page_file(1, $slug))) {
        $relatedNivelLinks[] = [
            'text' => $title,
            'url' => seo_build_href($base, $slug),
        ];
    } else {
        $relatedNivelKws[] = $title;
    }
}

$data = $base;
?>
<!doctype html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <base href="<?= seo_e(seo_base_href($base)) ?>">

    <title><?= seo_e((string)$pageTitle) ?></title>
    <meta name="description" content="<?= seo_e((string)$pageDescription) ?>">
    <meta name="robots" content="<?= seo_e((string)$pageRobots) ?>">
    <link rel="canonical" href="<?= seo_e((string)$pageCanonical) ?>">

    <meta property="og:locale" content="es_MX">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= seo_e((string)$pageTitle) ?>">
    <meta property="og:description" content="<?= seo_e((string)$pageDescription) ?>">
    <meta property="og:url" content="<?= seo_e((string)$pageCanonical) ?>">
    <meta property="og:site_name" content="<?= seo_e((string)($base['informacion_general']['nombre'] ?? '')) ?>">
    <?php if ($pageOgImage): ?>
        <meta property="og:image" content="<?= seo_e((string)$pageOgImage) ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= seo_e((string)$pageTitle) ?>">
    <meta name="twitter:description" content="<?= seo_e((string)$pageDescription) ?>">
    <?php if ($pageOgImage): ?>
        <meta name="twitter:image" content="<?= seo_e((string)$pageOgImage) ?>">
    <?php endif; ?>

    <?php seo_require_component($base, 'head-metas'); ?>
    <link rel="stylesheet" href="<?= seo_e(seo_asset_url($base, 'seo-engine/seo-v2.css')) ?>?v=1">
    <?= seo_render_palette_v2_style($base) ?>
    <?= seo_render_schema_scripts($schemas) ?>
</head>
<body>

<?php seo_require_component($base, 'botones'); ?>
<?php seo_require_component($base, 'header'); ?>

<?= seo_render_page_v2b([
    'base' => $base,
    'page' => $page,
    'breadcrumbs' => $breadcrumbs,
    'hub_children' => $hubChildren,
    'sibling_topics' => $siblingTopics,
    'related_nivel_kws' => $relatedNivelKws,
    'related_nivel_links' => $relatedNivelLinks,
    'hero_image' => $heroImage,
    'hero_alt' => (string)($page['seo']['h1'] ?? $route['title1']),
]) ?>

<?php seo_require_component($base, 'footer'); ?>
<?php seo_require_component($base, 'js'); ?>

</body>
</html>
