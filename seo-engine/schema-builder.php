<?php

declare(strict_types=1);

function seo_schema_organization(array $base, string $siteUrl): array
{
    return [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => $base['informacion_general']['nombre'] ?? '',
        'url'      => $siteUrl,
        'email'    => $base['informacion_contacto']['correo'][0] ?? '',
        'telephone'=> $base['informacion_contacto']['telefono'][0] ?? ''
    ];
}

function seo_schema_service(array $base, array $page, string $canonical): array
{
    return [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => $page['schema']['service_name'] ?? ($page['seo']['h1'] ?? ''),
        'description' => $page['seo']['meta_description'] ?? '',
        'serviceType' => $base['informacion_general']['especialidad'] ?? '',
        'areaServed'  => $base['informacion_general']['ubicacion'] ?? '',
        'url'         => $canonical,
        'provider'    => [
            '@type'     => 'Organization',
            'name'      => $base['informacion_general']['nombre'] ?? '',
            'url'       => $base['seo_engine']['site_url'] ?? '',
            'email'     => $base['informacion_contacto']['correo'][0] ?? '',
            'telephone' => $base['informacion_contacto']['telefono'][0] ?? ''
        ]
    ];
}

function seo_schema_breadcrumbs(array $breadcrumbs): array
{
    $items = [];

    foreach ($breadcrumbs as $i => $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['name'],
            'item'     => $crumb['url']
        ];
    }

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items
    ];
}

function seo_schema_faq(array $faq): ?array
{
    if (empty($faq)) {
        return null;
    }

    $entities = [];
    foreach ($faq as $item) {
        $entities[] = [
            '@type' => 'Question',
            'name'  => $item['question'] ?? '',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => strip_tags(seo_markdown_to_html((string)($item['answer'] ?? '')))
            ]
        ];
    }

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities
    ];
}

function seo_render_schema_scripts(array $schemas): string
{
    $out = '';
    foreach ($schemas as $schema) {
        if (!$schema) {
            continue;
        }

        $out .= '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . '</script>' . "\n";
    }
    return $out;
}
