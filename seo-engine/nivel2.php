<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/schema-builder.php';

$_legacyFunciones = dirname(__DIR__) . '/componentes/funciones.php';
if (is_file($_legacyFunciones)) {
    require_once $_legacyFunciones;
}

$rawPage = seo_load_page(2, $route['slug1'], $route['slug2']);
if (empty($rawPage)) {
    seo_404();
}

$page = seo_resolve_page_data($rawPage, $base, $route);

$siteUrl = seo_site_url($base);
$siteRootHref = seo_base_href($base);
$canonical = seo_build_url($siteUrl, $route['slug1'], $route['slug2']);
$palette = seo_build_palette($base);

$pageTitle = $page['seo']['title'] ?? ($route['title2'] . ' | ' . ($base['informacion_general']['nombre'] ?? ''));
$pageDescription = $page['seo']['meta_description'] ?? ($base['informacion_general']['descripcion'] ?? '');
$pageCanonical = $canonical;
$pageRobots = $page['robots'] ?? ($rawPage['robots'] ?? 'index,follow');
$pageImageAsset = seo_resolve_existing_asset($base, [
    'seo-engine/assets/images/' . $route['slug1'] . '/' . $route['slug2'] . '.webp',
    'seo-engine/assets/images/' . $route['slug1'] . '.webp',
]);
$heroImage = (string)($pageImageAsset['relative_url'] ?? '');
$pageOgImage = (string)($pageImageAsset['absolute_url'] ?? '');

$breadcrumbsSchema = [
    ['name' => 'Inicio', 'url' => $siteUrl . '/'],
    ['name' => $route['title1'], 'url' => seo_build_url($siteUrl, $route['slug1'])],
    ['name' => $route['title2'], 'url' => $canonical],
];
$breadcrumbs = [
    ['name' => 'Inicio', 'url' => $siteRootHref],
    ['name' => $route['title1'], 'url' => seo_build_href($base, $route['slug1'])],
    ['name' => $route['title2'], 'url' => seo_build_href($base, $route['slug1'], $route['slug2'])],
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

$related = [];
foreach ($route['children'] as $slug => $child) {
    if ($slug === $route['slug2']) {
        continue;
    }

    $related[] = [
        'title' => $child['title'],
        'url' => seo_build_href($base, $route['slug1'], $child['slug']),
    ];
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

    <?php $useV2 = true; ?>

    <?php if ($useV2): ?>
        <link rel="stylesheet" href="<?= seo_e(seo_asset_url($base, 'seo-engine/seo-v2.css')) ?>?v=1">
        <?= seo_render_palette_v2_style($base) ?>
    <?php else: ?>
        <link rel="stylesheet" href="<?= seo_e(seo_asset_url($base, 'seo-engine/seo.css')) ?>?v=1">
        <?= seo_render_palette_style($palette) ?>
    <?php endif; ?>

    <?= seo_render_schema_scripts($schemas) ?>
</head>
<body>

<?php seo_require_component($base, 'botones'); ?>
<?php seo_require_component($base, 'header'); ?>

<?php
$renderCtx = [
    'base' => $base,
    'page' => $page,
    'breadcrumbs' => $breadcrumbs,
    'related' => $related,
    'related_title' => 'Más sobre ' . $route['title1'],
    'hero_image' => $heroImage,
    'hero_alt' => (string)($page['seo']['h1'] ?? $route['title2']),
];
echo $useV2
    ? seo_render_page_v2b($renderCtx)
    : seo_render_page_v2($renderCtx);
?>

<?php seo_require_component($base, 'footer'); ?>
<?php seo_require_component($base, 'js'); ?>

</body>
</html>
