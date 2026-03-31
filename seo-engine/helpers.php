<?php

declare(strict_types=1);

function seo_load_json(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $raw = file_get_contents($path);
    if ($raw === false) {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function seo_slug(string $text): string
{
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(
        ['á','é','í','ó','ú','ü','ñ'],
        ['a','e','i','o','u','u','n'],
        $text
    );
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
    $text = trim((string)$text, '-');

    return $text !== '' ? $text : 'pagina';
}

function seo_default_site_config(): array
{
    return [
        'seo_mode' => 'personalizado',
        'especialidad' => '',
        'ubicacion' => '',
        'site' => [
            'host' => '',
            'display_name' => '',
            'business_name' => '',
        ],
        'informacion_general' => [
            'nombre' => '',
            'descripcion' => '',
            'especialidad' => '',
            'ubicacion' => '',
        ],
        'informacion_contacto' => [
            'telefono' => [],
            'correo' => [],
            'whatsapp' => [],
            'horario' => [],
            'ubicacion' => [],
        ],
        'seo_engine' => [
            'adapter' => 'standalone',
            'head-metas' => '',
            'header' => '',
            'footer' => '',
            'botones' => '',
            'js' => '',
            'site_url' => '',
            'site_path' => '',
            'branding' => [
                'color_1' => '#65a59d',
                'color_2' => '#697d79',
            ],
        ],
    ];
}

function seo_default_base(): array
{
    return [
        'seo_mode' => 'personalizado',
        'especialidad' => '',
        'ubicacion' => '',
        'informacion_general' => [
            'nombre' => '',
            'descripcion' => '',
            'especialidad' => '',
            'ubicacion' => '',
        ],
        'informacion_contacto' => [
            'telefono' => [],
            'correo' => [],
            'whatsapp' => [],
            'horario' => [],
            'ubicacion' => [],
        ],
        'seo_engine' => seo_default_site_config()['seo_engine'],
    ];
}

function seo_load_site_config(): array
{
    return array_replace_recursive(
        seo_default_site_config(),
        seo_load_json(__DIR__ . '/site-config.json')
    );
}

function seo_page_mode(array $page, array $base = []): string
{
    $mode = strtolower(trim((string)($page['mode'] ?? $base['seo_mode'] ?? 'personalizado')));
    return $mode === 'general' ? 'general' : 'personalizado';
}

function seo_page_level(array $page, int $fallback = 1): int
{
    return (int)($page['nivel'] ?? $fallback);
}

function seo_page_type(array $page, int $level): string
{
    $type = trim((string)($page['page_type'] ?? ''));
    if ($type !== '') return $type;
    return $level === 1 ? 'hub' : 'transaccional';
}

function seo_normalize_faqs(array $page): array
{
    $faqs = $page['faqs'] ?? $page['faq'] ?? [];
    if (!is_array($faqs)) return [];

    $normalized = [];
    foreach ($faqs as $faq) {
        $pregunta = trim((string)($faq['pregunta'] ?? $faq['question'] ?? ''));
        $respuesta = trim((string)($faq['respuesta'] ?? $faq['answer'] ?? ''));
        if ($pregunta === '' && $respuesta === '') continue;
        $normalized[] = [
            'pregunta' => $pregunta,
            'respuesta' => $respuesta,
        ];
    }

    return $normalized;
}

function seo_normalize_blocks(array $page): array
{
    $bloques = $page['seccion_seo']['bloques'] ?? [];
    if (!is_array($bloques)) return [];

    $normalized = [];
    foreach ($bloques as $bloque) {
        $h3Items = [];
        if (is_array($bloque['h3'] ?? null)) {
            foreach ($bloque['h3'] as $h3) {
                $titulo = trim((string)($h3['titulo'] ?? ''));
                $contenido = trim((string)($h3['contenido'] ?? ''));
                if ($titulo === '' && $contenido === '') continue;
                $h3Items[] = [
                    'titulo' => $titulo,
                    'contenido' => $contenido,
                ];
            }
        }

        $normalized[] = [
            'h2' => trim((string)($bloque['h2'] ?? '')),
            'descripcion' => trim((string)($bloque['descripcion'] ?? '')),
            'h3' => $h3Items,
        ];
    }

    return $normalized;
}

function seo_adapt_personalizado_page(array $page, array $base, array $route = []): array
{
    $level = seo_page_level($page, (int)($route['level'] ?? 1));

    return [
        'mode' => 'personalizado',
        'nivel' => $level,
        'page_type' => seo_page_type($page, $level),
        'seo' => is_array($page['seo'] ?? null) ? $page['seo'] : [],
        'secciones_relacionadas_nivel' => is_array($page['secciones_relacionadas_nivel'] ?? null) ? array_values($page['secciones_relacionadas_nivel']) : [],
        'seccion_seo' => [
            'introduccion' => trim((string)($page['seccion_seo']['introduccion'] ?? '')),
            'bloques' => seo_normalize_blocks($page),
        ],
        'beneficios_contacto' => is_array($page['beneficios_contacto'] ?? null) ? array_values($page['beneficios_contacto']) : [],
        'schema' => is_array($page['schema'] ?? null) ? $page['schema'] : ['tipo_recomendado' => '', 'json_ld' => []],
        'faqs' => seo_normalize_faqs($page),
        'cta' => trim((string)($page['cta'] ?? '')),
    ];
}

function seo_adapt_general_page(array $page, array $base, array $route = []): array
{
    $level = seo_page_level($page, (int)($route['level'] ?? 1));

    return [
        'mode' => 'general',
        'nivel' => $level,
        'page_type' => seo_page_type($page, $level),
        'seo' => is_array($page['seo'] ?? null) ? $page['seo'] : [],
        'secciones_relacionadas_nivel' => is_array($page['secciones_relacionadas_nivel'] ?? null) ? array_values($page['secciones_relacionadas_nivel']) : [],
        'seccion_seo' => [
            'introduccion' => trim((string)($page['seccion_seo']['introduccion'] ?? '')),
            'bloques' => seo_normalize_blocks($page),
        ],
        'beneficios_contacto' => is_array($page['beneficios_contacto'] ?? null) ? array_values($page['beneficios_contacto']) : [],
        'schema' => is_array($page['schema'] ?? null) ? $page['schema'] : ['tipo_recomendado' => '', 'json_ld' => []],
        'faqs' => seo_normalize_faqs($page),
        'cta' => trim((string)($page['cta'] ?? '')),
    ];
}

function seo_resolve_page_data(array $page, array $base, array $route = []): array
{
    $mode = seo_page_mode($page, $base);
    $resolved = $mode === 'general'
        ? seo_adapt_general_page($page, $base, $route)
        : seo_adapt_personalizado_page($page, $base, $route);

    if (empty($resolved['seo']['h1'])) {
        $resolved['seo']['h1'] = (string)($route['title2'] ?? $route['title1'] ?? '');
    }
    if (empty($resolved['seo']['title'])) {
        $resolved['seo']['title'] = (string)($resolved['seo']['h1'] ?? '');
    }
    if (empty($resolved['seo']['meta_description'])) {
        $resolved['seo']['meta_description'] = (string)($base['informacion_general']['descripcion'] ?? '');
    }

    return $resolved;
}

function seo_load_base(): array
{
    $base = array_replace_recursive(
        seo_default_base(),
        seo_load_json(dirname(__DIR__) . '/assets/json/base.json')
    );
    $config = seo_load_site_config();

    $base['seo_mode'] = $config['seo_mode'] ?: ($base['seo_mode'] ?? 'personalizado');
    if (($config['especialidad'] ?? '') !== '') {
        $base['especialidad'] = $config['especialidad'];
        if (($base['informacion_general']['especialidad'] ?? '') === '') {
            $base['informacion_general']['especialidad'] = $config['especialidad'];
        }
    }
    if (($config['ubicacion'] ?? '') !== '') {
        $base['ubicacion'] = $config['ubicacion'];
        if (($base['informacion_general']['ubicacion'] ?? '') === '') {
            $base['informacion_general']['ubicacion'] = $config['ubicacion'];
        }
    }

    if (($base['informacion_general']['nombre'] ?? '') === '') {
        $base['informacion_general']['nombre'] = (string)($config['site']['business_name'] ?? $config['site']['display_name'] ?? '');
    }

    if (!empty($config['informacion_general']) && is_array($config['informacion_general'])) {
        $base['informacion_general'] = array_replace_recursive(
            $base['informacion_general'] ?? [],
            $config['informacion_general']
        );
    }

    if (!empty($config['informacion_contacto']) && is_array($config['informacion_contacto'])) {
        $base['informacion_contacto'] = array_replace_recursive(
            $base['informacion_contacto'] ?? [],
            $config['informacion_contacto']
        );
    }

    $base['seo_engine'] = array_replace_recursive(
        $base['seo_engine'] ?? [],
        $config['seo_engine'] ?? []
    );

    if (($base['seo_engine']['site_url'] ?? '') === '' && !empty($config['site']['host'])) {
        $base['seo_engine']['site_url'] = 'https://' . $config['site']['host'];
    }

    return $base;
}

/**
 * Carga y normaliza mapa-seo.json.
 *
 * Soporta DOS formatos para cada entrada nivel1:
 *
 * Formato A — clave = título legible, valor = array de títulos/objetos (formato original):
 *   "Problemas de vida o crisis personales": ["Título hijo 1", ...]
 *
 * Formato B — clave = slug, valor = array de slugs o valor = objeto con title+children (nuevo):
 *   "problemas-de-vida-o-crisis-personales": ["slug-hijo-1", ...]
 *   "problemas-de-vida-o-crisis-personales": { "title": "Título legible", "children": [...] }
 *
 * En Formato B sin title, el slug se humaniza (guiones → espacios, primera letra mayúscula)
 * como título de fallback.
 */
function seo_load_map(): array
{
    $raw = seo_load_json(__DIR__ . '/mapa-seo.json');
    $map = [];

    // Convierte slug a título legible de último recurso ("mi-slug" → "Mi slug")
    $slugToTitle = static fn (string $s): string =>
        ucfirst(str_replace('-', ' ', $s));

    foreach (($raw['seo'] ?? []) as $key => $value) {
        // Formato B objeto: { "title": "...", "children": [...] }
        if (is_array($value) && isset($value['title'], $value['children'])) {
            $title    = (string)$value['title'];
            $slug1    = seo_slug($title) ?: seo_slug($key);
            $children = (array)$value['children'];
        }
        // Formato A / B array: clave puede ser título o slug, valor = array de hijos
        elseif (is_array($value)) {
            $slug1    = seo_slug((string)$key);
            // Si la clave ya es un slug (minúsculas, sin tildes) el title se humaniza;
            // si es un título legible (tiene mayúsculas / tildes) lo usamos directamente.
            $title    = (seo_slug($key) === $key)
                ? $slugToTitle($key)
                : (string)$key;
            $children = $value;
        }
        else {
            continue;
        }

        $map[$slug1] = [
            'title'    => $title,
            'slug'     => $slug1,
            'children' => [],
        ];

        foreach ($children as $item) {
            // Hijo como string: puede ser slug directo o título
            if (is_string($item)) {
                $slug2       = seo_slug($item);
                $childTitle  = (seo_slug($item) === $item) ? $slugToTitle($item) : $item;
                $map[$slug1]['children'][$slug2] = [
                    'title' => $childTitle,
                    'slug'  => $slug2,
                ];
                continue;
            }
            // Hijo como objeto: { "title": "...", "slug": "..." }
            if (is_array($item) && !empty($item['title'])) {
                $slug2 = !empty($item['slug'])
                    ? (string)$item['slug']
                    : seo_slug((string)$item['title']);
                $map[$slug1]['children'][$slug2] = [
                    'title' => (string)$item['title'],
                    'slug'  => $slug2,
                ];
            }
        }
    }

    return $map;
}

function seo_get_page_file(int $level, string $slug1, ?string $slug2 = null): string
{
    if ($level === 1) {
        return __DIR__ . '/pages/n1--' . $slug1 . '.json';
    }

    return __DIR__ . '/pages/n2--' . $slug1 . '--' . (string)$slug2 . '.json';
}

function seo_load_page(int $level, string $slug1, ?string $slug2 = null): array
{
    return seo_load_json(seo_get_page_file($level, $slug1, $slug2));
}

function seo_detect_scheme(): string
{
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    return $https ? 'https' : 'http';
}

function seo_site_origin(array $base): string
{
    $configured = trim((string)($base['seo_engine']['site_url'] ?? ''));
    if ($configured !== '') {
        $parts = parse_url($configured);
        if (!empty($parts['scheme']) && !empty($parts['host'])) {
            $origin = $parts['scheme'] . '://' . $parts['host'];
            if (!empty($parts['port'])) {
                $origin .= ':' . $parts['port'];
            }
            return rtrim($origin, '/');
        }
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return seo_detect_scheme() . '://' . $host;
}

function seo_site_path(array $base): string
{
    $configuredPath = trim((string)($base['seo_engine']['site_path'] ?? ''));
    if ($configuredPath !== '') {
        $configuredPath = '/' . trim($configuredPath, '/');
        return $configuredPath === '/' ? '' : rtrim($configuredPath, '/');
    }

    $configuredUrl = trim((string)($base['seo_engine']['site_url'] ?? ''));
    if ($configuredUrl !== '') {
        $parts = parse_url($configuredUrl);
        $path = trim((string)($parts['path'] ?? ''), '/');
        if ($path !== '') {
            return '/' . $path;
        }
    }

    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    $path = trim(str_replace('\\', '/', dirname(dirname($scriptName))), '/');
    return $path !== '' ? '/' . $path : '';
}

function seo_site_url(array $base): string
{
    return rtrim(seo_site_origin($base) . seo_site_path($base), '/');
}

function seo_base_href(array $base): string
{
    $path = seo_site_path($base);
    return $path === '' ? '/' : rtrim($path, '/') . '/';
}

function seo_build_path(string $basePath, string $slug1, ?string $slug2 = null): string
{
    $basePath = $basePath !== '' ? '/' . trim($basePath, '/') : '';
    $path = $basePath . '/' . trim($slug1, '/');
    if ($slug2 !== null && $slug2 !== '') {
        $path .= '/' . trim($slug2, '/');
    }
    return rtrim($path, '/') . '/';
}

function seo_build_url(string $siteUrl, string $slug1, ?string $slug2 = null): string
{
    return rtrim($siteUrl, '/') . seo_build_path('', $slug1, $slug2);
}

function seo_build_href(array $base, string $slug1, ?string $slug2 = null): string
{
    return seo_build_path(seo_site_path($base), $slug1, $slug2);
}

function seo_asset_url(array $base, string $relativePath): string
{
    $basePath = seo_site_path($base);
    $assetPath = trim($relativePath, '/');

    if ($assetPath === '') {
        return seo_base_href($base);
    }

    return ($basePath !== '' ? rtrim($basePath, '/') : '') . '/' . $assetPath;
}

function seo_absolute_asset_url(array $base, string $relativePath): string
{
    return rtrim(seo_site_origin($base), '/') . seo_asset_url($base, $relativePath);
}

function seo_local_asset_path(string $relativePath): string
{
    return dirname(__DIR__) . '/' . ltrim($relativePath, '/');
}

function seo_asset_exists(string $relativePath): bool
{
    $path = seo_local_asset_path($relativePath);
    return is_file($path);
}

function seo_resolve_existing_asset(array $base, array $relativePaths): array
{
    foreach ($relativePaths as $relativePath) {
        $relativePath = trim((string)$relativePath);
        if ($relativePath === '') {
            continue;
        }
        if (!seo_asset_exists($relativePath)) {
            continue;
        }

        return [
            'path' => $relativePath,
            'relative_url' => seo_asset_url($base, $relativePath),
            'absolute_url' => seo_absolute_asset_url($base, $relativePath),
        ];
    }

    return [
        'path' => '',
        'relative_url' => '',
        'absolute_url' => '',
    ];
}

function seo_e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function seo_hex_to_rgb(string $hex): array
{
    $hex = ltrim(trim($hex), '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }

    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}

function seo_rgb_to_hex(array $rgb): string
{
    return sprintf('#%02x%02x%02x', (int)$rgb[0], (int)$rgb[1], (int)$rgb[2]);
}

function seo_mix(string $hexA, string $hexB, float $ratio = 0.5): string
{
    [$r1, $g1, $b1] = seo_hex_to_rgb($hexA);
    [$r2, $g2, $b2] = seo_hex_to_rgb($hexB);

    $r = (int) round($r1 + ($r2 - $r1) * $ratio);
    $g = (int) round($g1 + ($g2 - $g1) * $ratio);
    $b = (int) round($b1 + ($b2 - $b1) * $ratio);

    return seo_rgb_to_hex([$r, $g, $b]);
}

function seo_build_palette(array $base): array
{
    $c1 = (string)($base['seo_engine']['branding']['color_1'] ?? '#7C3AED');
    $c2 = (string)($base['seo_engine']['branding']['color_2'] ?? '#EC4899');

    return [
        '--dwseo-c1'      => $c1,
        '--dwseo-c2'      => $c2,
        '--dwseo-c1-50'   => seo_mix($c1, '#ffffff', 0.92),
        '--dwseo-c1-100'  => seo_mix($c1, '#ffffff', 0.84),
        '--dwseo-c1-700'  => seo_mix($c1, '#000000', 0.18),
        '--dwseo-c2-50'   => seo_mix($c2, '#ffffff', 0.92),
        '--dwseo-c2-100'  => seo_mix($c2, '#ffffff', 0.84),
        '--dwseo-c2-700'  => seo_mix($c2, '#000000', 0.18),
        '--dwseo-text'    => '#1f2937',
        '--dwseo-muted'   => '#6b7280',
        '--dwseo-border'  => '#e5e7eb',
        '--dwseo-bg'      => '#ffffff',
        '--dwseo-soft'    => '#f8fafc',
        '--dwseo-shadow'  => '0 18px 40px rgba(17,24,39,.08)'
    ];
}

function seo_render_palette_style(array $palette): string
{
    $out = ":root{\n";
    foreach ($palette as $key => $value) {
        $out .= $key . ':' . $value . ";\n";
    }
    $out .= "}\n";

    return '<style>' . $out . '</style>';
}

/**
 * Resalta palabras clave en texto plano y devuelve HTML seguro.
 *
 * - $mainKw       → PRIMERA ocurrencia: <a><strong> (interlink al nivel1)
 * - $interlinks   → PRIMERA ocurrencia de cada uno: <a><strong> (interlinks adicionales)
 *                   Formato: [['text' => '...', 'url' => '...'], ...]
 * - $secondaryKws → TODAS las ocurrencias de cada una: <strong>
 *
 * Trabaja con offsets de caracteres MB para no solapar reemplazos.
 * El texto de entrada NO debe estar escapado.
 */
function seo_keyword_compare_key(string $text): string
{
    $text = preg_replace('/[\p{P}\p{S}]+/u', ' ', trim($text)) ?? trim($text);
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
    return mb_strtolower(trim($text), 'UTF-8');
}

function seo_trim_keyword_window(array $tokens): array
{
    $stopwords = [
        'a', 'al', 'con', 'de', 'del', 'el', 'en', 'la', 'las', 'los',
        'o', 'para', 'por', 'un', 'una', 'y',
    ];

    while (!empty($tokens)) {
        $first = seo_keyword_compare_key((string)$tokens[0]);
        if (!in_array($first, $stopwords, true)) {
            break;
        }
        array_shift($tokens);
    }

    while (!empty($tokens)) {
        $last = seo_keyword_compare_key((string)$tokens[count($tokens) - 1]);
        if (!in_array($last, $stopwords, true)) {
            break;
        }
        array_pop($tokens);
    }

    return array_values($tokens);
}

function seo_secondary_keyword_candidates(array $secondaryKws, string $mainKw = ''): array
{
    $candidates = [];
    $seen = [];
    $mainKey = seo_keyword_compare_key($mainKw);

    foreach ($secondaryKws as $rawKw) {
        $kw = trim((string)$rawKw);
        if ($kw === '') {
            continue;
        }

        $tokens = preg_split('/\s+/u', $kw, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($tokens) || empty($tokens)) {
            continue;
        }

        $wordCount = count($tokens);
        $minWindow = $wordCount >= 4 ? 3 : $wordCount;

        for ($size = $wordCount; $size >= $minWindow; $size--) {
            for ($start = 0; $start <= $wordCount - $size; $start++) {
                $window = seo_trim_keyword_window(array_slice($tokens, $start, $size));
                if (count($window) < $minWindow) {
                    continue;
                }

                $candidate = trim(implode(' ', $window));
                if ($candidate === '') {
                    continue;
                }

                $key = seo_keyword_compare_key($candidate);
                if ($key === '' || isset($seen[$key]) || ($mainKey !== '' && $key === $mainKey)) {
                    continue;
                }

                $seen[$key] = true;
                $candidates[] = $candidate;
            }
        }
    }

    usort($candidates, static fn ($a, $b) => mb_strlen((string)$b, 'UTF-8') - mb_strlen((string)$a, 'UTF-8'));
    return $candidates;
}

function seo_highlight_keywords(
    string $text,
    string $mainKw,
    array  $secondaryKws,
    string $mainUrl = '',
    array  $interlinks = [],
    array  $otherStrongKws = []
): string {
    if ($text === '') {
        return '';
    }

    $replacements = [];   // [[offset, len, html], ...]
    $usedRanges   = [];   // [[offset, len], ...]

    // Comprueba si [pos, len] solapa con rangos ya usados
    $overlaps = static function (int $pos, int $len, array $ranges): bool {
        $end = $pos + $len;
        foreach ($ranges as [$s, $l]) {
            if ($pos < $s + $l && $end > $s) {
                return true;
            }
        }
        return false;
    };

    // Añade la PRIMERA ocurrencia de $kw (para la keyword principal)
    $addFirst = static function (string $kw, string $wrapHtml) use (
        $text, &$replacements, &$usedRanges, $overlaps
    ): void {
        if ($kw === '') {
            return;
        }
        $pos = mb_stripos($text, $kw, 0, 'UTF-8');
        if ($pos === false) {
            return;
        }
        $len = mb_strlen($kw, 'UTF-8');
        if ($overlaps($pos, $len, $usedRanges)) {
            return;
        }
        $original = mb_substr($text, $pos, $len, 'UTF-8');
        $replacements[] = [$pos, $len, sprintf($wrapHtml, htmlspecialchars($original, ENT_QUOTES, 'UTF-8'))];
        $usedRanges[]   = [$pos, $len];
    };

    // Añade TODAS las ocurrencias de $kw (para keywords secundarias)
    $addAll = static function (string $kw, string $wrapHtml) use (
        $text, &$replacements, &$usedRanges, $overlaps
    ): void {
        if ($kw === '') {
            return;
        }
        $len      = mb_strlen($kw, 'UTF-8');
        $textLen  = mb_strlen($text, 'UTF-8');
        $from     = 0;
        while ($from < $textLen) {
            $pos = mb_stripos($text, $kw, $from, 'UTF-8');
            if ($pos === false) {
                break;
            }
            if (!$overlaps($pos, $len, $usedRanges)) {
                $original = mb_substr($text, $pos, $len, 'UTF-8');
                $replacements[] = [$pos, $len, sprintf($wrapHtml, htmlspecialchars($original, ENT_QUOTES, 'UTF-8'))];
                $usedRanges[]   = [$pos, $len];
            }
            $from = $pos + $len;
        }
    };

    // Secundarias SEO: todas las ocurrencias, con variantes parciales seguras
    foreach (seo_secondary_keyword_candidates($secondaryKws, $mainKw) as $kw) {
        $addAll($kw, '<strong class="seopg__kw">%s</strong>');
    }

    // Keyword principal: bold + interlink (solo primera ocurrencia libre)
    $href    = $mainUrl !== '' ? ' href="' . htmlspecialchars($mainUrl, ENT_QUOTES, 'UTF-8') . '"' : '';
    $mainTag = $mainUrl !== ''
        ? '<a' . $href . ' class="seopg__kw-link"><strong class="seopg__kw">%s</strong></a>'
        : '<strong class="seopg__kw">%s</strong>';
    $addFirst($mainKw, $mainTag);

    // Interlinks adicionales: bold + interlink (primera ocurrencia cada uno)
    // Ordenados de mayor a menor longitud para no marcar subcadenas antes que la frase completa
    $sortedLinks = $interlinks;
    usort($sortedLinks, static fn ($a, $b) => mb_strlen((string)($b['text'] ?? ''), 'UTF-8') - mb_strlen((string)($a['text'] ?? ''), 'UTF-8'));
    foreach ($sortedLinks as $link) {
        $linkText = trim((string)($link['text'] ?? ''));
        $linkUrl  = trim((string)($link['url']  ?? ''));
        if ($linkText === '' || $linkUrl === '') {
            continue;
        }
        $linkHref = htmlspecialchars($linkUrl, ENT_QUOTES, 'UTF-8');
        $addFirst($linkText, '<a href="' . $linkHref . '" class="seopg__kw-link"><strong class="seopg__kw">%s</strong></a>');
    }

    // Extras solo en strong: transaccionales, relacionadas u otras frases auxiliares
    $sorted = $otherStrongKws;
    usort($sorted, static fn ($a, $b) => mb_strlen((string)$b, 'UTF-8') - mb_strlen((string)$a, 'UTF-8'));
    foreach ($sorted as $kw) {
        $addAll(trim((string)$kw), '<strong class="seopg__kw">%s</strong>');
    }

    if (empty($replacements)) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    // Ordenar por posición y construir la salida
    usort($replacements, static fn ($a, $b) => $a[0] - $b[0]);

    $out    = '';
    $cursor = 0;
    foreach ($replacements as [$pos, $len, $html]) {
        if ($cursor < $pos) {
            $out .= htmlspecialchars(mb_substr($text, $cursor, $pos - $cursor, 'UTF-8'), ENT_QUOTES, 'UTF-8');
        }
        $out   .= $html;
        $cursor = $pos + $len;
    }
    $total = mb_strlen($text, 'UTF-8');
    if ($cursor < $total) {
        $out .= htmlspecialchars(mb_substr($text, $cursor, null, 'UTF-8'), ENT_QUOTES, 'UTF-8');
    }

    return $out;
}

/**
 * Inyecta los tokens --seopg-c1 y --seopg-c2 para el sistema v2.
 * Solo necesita los dos colores del cliente; el resto lo deriva el CSS con color-mix().
 */
function seo_render_palette_v2_style(array $base): string
{
    $c1 = (string)($base['seo_engine']['branding']['color_1'] ?? '');
    $c2 = (string)($base['seo_engine']['branding']['color_2'] ?? '');

    // Si no hay colores en base.json, no inyectar nada:
    // los defaults del CSS (definidos en .seopg{}) quedan activos.
    if ($c1 === '' && $c2 === '') {
        return '';
    }

    $vars = '';
    if ($c1 !== '') $vars .= '--seopg-c1:' . seo_e($c1) . ';';
    if ($c2 !== '') $vars .= '--seopg-c2:' . seo_e($c2) . ';';

    return "<style>.seopg{{$vars}}</style>\n";
}

/**
 * Render alternativo v2b — usa el nuevo sistema de diseño seo-v2.css
 * Misma firma que seo_render_page_v2() para intercambio directo.
 */
function seo_render_page_v2b(array $ctx): string
{
    $page    = $ctx['page'];
    $base    = $ctx['base'];
    $phUrl   = seo_phone_url($base);
    $mailUrl = seo_mail_url($base);
    $phone   = (string)($base['informacion_contacto']['telefono'][0] ?? '');
    $email   = (string)($base['informacion_contacto']['correo'][0] ?? '');
    $horario = (string)($base['informacion_contacto']['horario'][0]['horas'] ?? '');

    // ── Iconos Lucide (inline SVG, stroke="currentColor") ───────────
    $svg = 'xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"'
         . ' stroke="currentColor" stroke-width="2"'
         . ' stroke-linecap="round" stroke-linejoin="round"'
         . ' aria-hidden="true" focusable="false"';
    $iconWa      = '<svg ' . $svg . ' width="16" height="16"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>';
    $iconPhone   = '<svg ' . $svg . ' width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
    $iconMail    = '<svg ' . $svg . ' width="16" height="16"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>';
    $iconClock   = '<svg ' . $svg . ' width="13" height="13"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
    $iconChevron = '<svg class="seopg__faq-icon" ' . $svg . ' width="16" height="16"><path d="m6 9 6 6 6-6"/></svg>';
    $iconArrow   = '<svg class="seopg__related-arrow" ' . $svg . ' width="14" height="14"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';

    // WhatsApp y teléfono — texto y URL estandarizados (sin depender de ctas en JSON)
    $waUrl         = seo_whatsapp_url($base);
    $ctaWa         = 'Enviar WhatsApp Atención Segura';
    $ctaPhone      = 'Atención urgente por Llamada';

    $h1            = (string)($page['seo']['h1']                      ?? '');
    $intro         = (string)($page['seccion_seo']['introduccion']    ?? '');
    $bloques       = $page['seccion_seo']['bloques']                  ?? [];
    $faqs          = $page['faqs']          ?? $page['faq']           ?? [];
    $beneficios    = $page['beneficios_contacto']                     ?? [];
    $heroImage     = (string)($ctx['hero_image']                      ?? '');
    $heroAlt       = (string)($ctx['hero_alt']                        ?? $h1);
    $breadcrumbs   = $ctx['breadcrumbs']                              ?? [];
    $related       = $ctx['related']                                  ?? [];
    $hubChildren   = $ctx['hub_children']                             ?? [];  // nivel1 hub
    $siblingTopics = $ctx['sibling_topics']                           ?? [];  // nivel1: temas hermanos para CTA band
    $relatedTitle  = (string)($ctx['related_title']                   ?? 'Temas relacionados');

    // ── Keywords para highlighting ───────────────────────────────────
    $mainKw             = (string)($page['seo']['palabra_clave_principal']         ?? '');
    $secondaryKws       = (array) ($page['seo']['palabras_clave_secundarias']      ?? []);
    $transKws           = (array) ($page['seo']['preguntas_seo_transaccionales']   ?? []);
    $relatedNivelKws    = (array) ($ctx['related_nivel_kws']                       ?? []);  // nivel1: secciones sin JSON → solo bold
    $relatedNivelLinks  = (array) ($ctx['related_nivel_links']                     ?? []);  // nivel1: secciones con JSON → bold + interlink
    // Bold en todas las ocurrencias: secundarias + transaccionales + secciones hermanas sin página
    $otherStrongKws = array_merge($transKws, $relatedNivelKws);
    // URL del nivel1: disponible en nivel2 (3 breadcrumbs), el índice [1] es el padre
    $mainKwUrl    = count($breadcrumbs) >= 3 ? (string)($breadcrumbs[1]['url'] ?? '') : '';
    // Closure rápido: aplica highlighting completo a texto plano
    $hl = static fn (string $t): string => seo_highlight_keywords($t, $mainKw, $secondaryKws, $mainKwUrl, $relatedNivelLinks, $otherStrongKws);

    // Eyebrow: nombre de la categoría (penúltimo breadcrumb)
    $eyebrow = '';
    if (count($breadcrumbs) >= 2) {
        $eyebrow = (string)($breadcrumbs[count($breadcrumbs) - 2]['name'] ?? '');
    }

    ob_start();
    ?>
<div class="seopg">

    <!-- BREADCRUMBS -->
    <nav class="seopg__crumbs" aria-label="Ruta de navegación">
        <div class="seopg__inner">
            <ol>
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <?php $isLast = ($i === count($breadcrumbs) - 1); ?>
                    <li>
                        <?php if (!$isLast && !empty($crumb['url'])): ?>
                            <a href="<?= seo_e((string)$crumb['url']) ?>"><?= seo_e((string)$crumb['name']) ?></a>
                        <?php else: ?>
                            <span aria-current="page"><?= seo_e((string)$crumb['name']) ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </nav>

    <!-- HERO -->
    <section class="seopg__hero" aria-labelledby="seopg-h1">
        <div class="seopg__inner seopg__hero-inner">

            <div class="seopg__hero-content">
                <?php if ($eyebrow !== ''): ?>
                    <span class="seopg__hero-eyebrow"><?= seo_e($eyebrow) ?></span>
                <?php endif; ?>

                <h1 class="seopg__h1" id="seopg-h1"><?= seo_e($h1) ?></h1>

                <?php if ($intro !== ''): ?>
                    <p class="seopg__lead"><?= $hl($intro) ?></p>
                <?php endif; ?>

                <div class="seopg__hero-actions">
                    <a class="seopg__btn seopg__btn--primary" href="<?= seo_e($waUrl) ?>">
                        <?= $iconWa ?><?= seo_e($ctaWa) ?>
                    </a>
                    <a class="seopg__btn seopg__btn--outline" href="<?= seo_e($phUrl) ?>">
                        <?= $iconPhone ?><?= seo_e($ctaPhone) ?>
                    </a>
                </div>
            </div>

            <?php if ($heroImage !== ''): ?>
                <figure class="seopg__hero-fig">
                    <img src="<?= seo_e($heroImage) ?>"
                         alt="<?= seo_e($heroAlt) ?>"
                         width="600" height="450"
                         loading="eager"
                         decoding="async">
                </figure>
            <?php endif; ?>

        </div>
    </section>

    <!-- BODY -->
    <div class="seopg__inner">
        <div class="seopg__body">

            <main class="seopg__main" id="seopg-main">

                <!-- BLOQUES DE CONTENIDO -->
                <?php if (!empty($bloques)): ?>
                    <div class="seopg__blocks">
                        <?php foreach ($bloques as $bloque): ?>
                            <section class="seopg__block">
                                <?php if (!empty($bloque['h2'])): ?>
                                    <h2 class="seopg__block-h2"><?= seo_e((string)$bloque['h2']) ?></h2>
                                <?php endif; ?>
                                <?php if (!empty($bloque['descripcion'])): ?>
                                    <p class="seopg__block-desc"><?= $hl((string)$bloque['descripcion']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($bloque['h3'])): ?>
                                    <div class="seopg__h3-grid">
                                        <?php foreach ($bloque['h3'] as $sub): ?>
                                            <article class="seopg__h3-card">
                                                <?php if (!empty($sub['titulo'])): ?>
                                                    <h3 class="seopg__h3"><?= seo_e((string)$sub['titulo']) ?></h3>
                                                <?php endif; ?>
                                                <?php if (!empty($sub['contenido'])): ?>
                                                    <p class="seopg__h3-text"><?= $hl((string)$sub['contenido']) ?></p>
                                                <?php endif; ?>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <!-- FAQ -->
                <?php if (!empty($faqs)): ?>
                    <section class="seopg__faq" aria-labelledby="seopg-faq-title">
                        <h2 class="seopg__faq-title" id="seopg-faq-title">Preguntas frecuentes</h2>
                        <div class="seopg__faq-list">
                            <?php foreach ($faqs as $i => $faq): ?>
                                <details class="seopg__faq-item">
                                    <summary class="seopg__faq-q">
                                        <span class="seopg__faq-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                                        <span class="seopg__faq-q-text"><?= seo_e((string)($faq['pregunta'] ?? $faq['question'] ?? '')) ?></span>
                                        <?= $iconChevron ?>
                                    </summary>
                                    <div class="seopg__faq-a">
                                        <p><?= $hl((string)($faq['respuesta'] ?? $faq['answer'] ?? '')) ?></p>
                                    </div>
                                </details>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- RELACIONADOS -->
                <?php if (!empty($related)): ?>
                    <section class="seopg__related" aria-labelledby="seopg-related-title">
                        <h2 class="seopg__related-title" id="seopg-related-title"><?= seo_e($relatedTitle) ?></h2>
                        <div class="seopg__related-list">
                            <?php foreach ($related as $rel): ?>
                                <a class="seopg__related-item" href="<?= seo_e((string)$rel['url']) ?>">
                                    <span><?= seo_e((string)$rel['title']) ?></span>
                                    <?= $iconArrow ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </main>

            <!-- ASIDE -->
            <aside class="seopg__aside" aria-label="Beneficios y contacto">

                <!-- Hub de hijos (nivel1): aparece al tope del lateral -->
                <?php if (!empty($hubChildren)): ?>
                    <div class="seopg__card">
                        <div class="seopg__card-head">
                            <span class="seopg__card-dot"></span>
                            <h2>Temas de esta sección</h2>
                        </div>
                        <nav class="seopg__hub-aside-list" aria-label="Temas de esta sección">
                            <?php foreach ($hubChildren as $child): ?>
                                <a class="seopg__hub-aside-item" href="<?= seo_e((string)$child['url']) ?>">
                                    <?php if (!empty($child['image'])): ?>
                                        <img class="seopg__hub-aside-img"
                                             src="<?= seo_e((string)$child['image']) ?>"
                                             alt="<?= seo_e((string)$child['title']) ?>"
                                             width="56" height="56"
                                             loading="lazy"
                                             decoding="async"
                                             onerror="this.style.display='none'">
                                    <?php endif; ?>
                                    <div class="seopg__hub-aside-info">
                                        <p class="seopg__hub-aside-title"><?= seo_e((string)$child['title']) ?></p>
                                        <?php if (!empty($child['extracto'])): ?>
                                            <p class="seopg__hub-aside-excerpt"><?= seo_e((string)$child['extracto']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="seopg__hub-aside-arrow" aria-hidden="true"><?= $iconArrow ?></span>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                <?php endif; ?>

                <!-- Beneficios -->
                <?php if (!empty($beneficios)): ?>
                    <div class="seopg__card">
                        <div class="seopg__card-head">
                            <span class="seopg__card-dot"></span>
                            <h2>Beneficios</h2>
                        </div>
                        <div class="seopg__card-body">
                            <ul class="seopg__checklist">
                                <?php foreach ($beneficios as $b): ?>
                                    <li><?= seo_e((string)$b) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Contacto -->
                <div class="seopg__card">
                    <div class="seopg__card-head">
                        <span class="seopg__card-dot"></span>
                        <h2>Contacto</h2>
                    </div>
                    <div class="seopg__contact-links">
                        <a class="seopg__contact-link seopg__contact-link--wa" href="<?= seo_e($waUrl) ?>">
                            <span class="seopg__contact-icon"><?= $iconWa ?></span>
                            <span class="seopg__contact-info">
                                <span class="seopg__contact-label">WhatsApp</span>
                                <span class="seopg__contact-sub">Escríbenos ahora</span>
                            </span>
                        </a>
                        <?php if ($phone !== ''): ?>
                            <a class="seopg__contact-link seopg__contact-link--ph" href="<?= seo_e($phUrl) ?>">
                                <span class="seopg__contact-icon"><?= $iconPhone ?></span>
                                <span class="seopg__contact-info">
                                    <span class="seopg__contact-label"><?= seo_e($phone) ?></span>
                                    <span class="seopg__contact-sub">Llamar ahora</span>
                                </span>
                            </a>
                        <?php endif; ?>
                        <?php if ($email !== ''): ?>
                            <a class="seopg__contact-link seopg__contact-link--em" href="<?= seo_e($mailUrl) ?>">
                                <span class="seopg__contact-icon"><?= $iconMail ?></span>
                                <span class="seopg__contact-info">
                                    <span class="seopg__contact-label"><?= seo_e($email) ?></span>
                                    <span class="seopg__contact-sub">Enviar correo</span>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($horario !== ''): ?>
                        <div class="seopg__horario"><?= $iconClock ?> <?= seo_e($horario) ?></div>
                    <?php endif; ?>
                </div>

            </aside>
        </div>
    </div>

    <!-- CTA BAND FINAL -->
    <?php
        // ── CTA Band — detecta tipo y ubicación desde schema.json_ld ───────
        $schemaLd      = $page['schema']['json_ld'] ?? [];
        $schemaType    = strtolower(trim((string)($schemaLd['@type'] ?? '')));
        $medicalTypes  = ['physician', 'medicalbusiness', 'medicalclinic', 'medicalorganization',
                          'hospital', 'dentist', 'medicalspecialty'];
        // Ubicación: address.addressLocality tiene prioridad; si no, areaServed (cuando no sea "Online")
        $ctaBandUbic   = '';
        if (in_array($schemaType, $medicalTypes, true)) {
            $locality = trim((string)($schemaLd['address']['addressLocality'] ?? ''));
            if ($locality !== '') {
                $ctaBandUbic = $locality;
            } else {
                $areaServed = trim((string)($schemaLd['areaServed'] ?? ''));
                if ($areaServed !== '' && strtolower($areaServed) !== 'online') {
                    $ctaBandUbic = $areaServed;
                }
            }
        }
        $isMedico = ($ctaBandUbic !== '');
        $isNivel1    = (count($breadcrumbs) === 2);

        if ($isMedico) {
            // — Variante médico con ubicación —
            $ctaBandBadge   = 'Atención médica en ' . htmlspecialchars($ctaBandUbic, ENT_QUOTES, 'UTF-8');
            $ctaBandHeading = 'Contactar a un médico en ' . htmlspecialchars($ctaBandUbic, ENT_QUOTES, 'UTF-8') . ' ahora';
            $ctaBandDesc    = 'Escríbenos por WhatsApp o llámanos, será un placer atenderte.';
            $ctaBandNote    = 'Solo para pacientes de ' . htmlspecialchars($ctaBandUbic, ENT_QUOTES, 'UTF-8');
        } else {
            // — Variante estándar —
            $ctaBandBadge   = 'DA EL SIGUIENTE PASO';
            $ctaBandHeading = 'Escríbenos por WhatsApp';
            $ctaBandDesc    = 'Resolvemos tus dudas y te compartimos información clara sobre '
                            . htmlspecialchars($mainKw, ENT_QUOTES, 'UTF-8')
                            . ' para ayudarte a identificar el siguiente paso.';
            if ($isNivel1 && count($siblingTopics) >= 2) {
                $ctaBandDesc .= ' También podemos orientarte sobre '
                             . htmlspecialchars($siblingTopics[0], ENT_QUOTES, 'UTF-8')
                             . ' y '
                             . htmlspecialchars($siblingTopics[1], ENT_QUOTES, 'UTF-8') . '.';
            } elseif ($isNivel1 && count($siblingTopics) === 1) {
                $ctaBandDesc .= ' También podemos orientarte sobre '
                             . htmlspecialchars($siblingTopics[0], ENT_QUOTES, 'UTF-8') . '.';
            }
            $ctaBandNote    = '';
        }
    ?>
    <section class="seopg__cta-band" aria-labelledby="seopg-cta-heading">
        <div class="seopg__inner seopg__cta-band-inner">
            <span class="seopg__cta-band-badge"><?= $ctaBandBadge ?></span>
            <h2 class="seopg__cta-band-heading" id="seopg-cta-heading"><?= $ctaBandHeading ?></h2>
            <p class="seopg__cta-band-sub"><?= $ctaBandDesc ?></p>
            <div class="seopg__cta-band-actions">
                <a class="seopg__btn seopg__btn--white seopg__btn--lg" href="<?= seo_e($waUrl) ?>">
                    <?= $iconWa ?><?= seo_e($ctaWa) ?>
                </a>
                <a class="seopg__btn seopg__btn--ghost-inv" href="<?= seo_e($phUrl) ?>">
                    <?= $iconPhone ?><?= seo_e($ctaPhone) ?>
                </a>
            </div>
            <?php if ($ctaBandNote !== ''): ?>
                <p class="seopg__cta-band-note"><?= seo_e($ctaBandNote) ?></p>
            <?php endif; ?>
        </div>
    </section>

</div>
    <?php
    return (string)ob_get_clean();
}

function seo_apply_interlinks_markdown(string $markdown, array $interlinks = []): string
{
    foreach ($interlinks as $item) {
        $anchor = trim((string)($item['anchor'] ?? ''));
        $url    = trim((string)($item['url'] ?? ''));

        if ($anchor === '' || $url === '') {
            continue;
        }

        $pattern = '/' . preg_quote($anchor, '/') . '/u';
        $replace = '[' . $anchor . '](' . $url . ')';

        $new = preg_replace($pattern, $replace, $markdown, 1);
        if ($new !== null && $new !== $markdown) {
            $markdown = $new;
        }
    }

    return $markdown;
}

function seo_inline_markdown(string $text): string
{
    $text = seo_e($text);
    $text = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('/\*(.+?)\*/u', '<em>$1</em>', $text) ?? $text;
    $text = preg_replace('/\[(.+?)\]\((.+?)\)/u', '<a href="$2">$1</a>', $text) ?? $text;
    return $text;
}

function seo_markdown_to_html(string $markdown): string
{
    $markdown = str_replace(["\r\n", "\r"], "\n", trim($markdown));
    if ($markdown === '') {
        return '';
    }

    $lines = explode("\n", $markdown);
    $html = '';
    $inList = false;

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '') {
            if ($inList) {
                $html .= '</ul>';
                $inList = false;
            }
            continue;
        }

        if (preg_match('/^###\s+(.+)$/u', $line, $m)) {
            if ($inList) {
                $html .= '</ul>';
                $inList = false;
            }
            $html .= '<h3>' . seo_inline_markdown($m[1]) . '</h3>';
            continue;
        }

        if (preg_match('/^##\s+(.+)$/u', $line, $m)) {
            if ($inList) {
                $html .= '</ul>';
                $inList = false;
            }
            $html .= '<h2>' . seo_inline_markdown($m[1]) . '</h2>';
            continue;
        }

        if (preg_match('/^-\s+(.+)$/u', $line, $m)) {
            if (!$inList) {
                $html .= '<ul>';
                $inList = true;
            }
            $html .= '<li>' . seo_inline_markdown($m[1]) . '</li>';
            continue;
        }

        if ($inList) {
            $html .= '</ul>';
            $inList = false;
        }

        $html .= '<p>' . seo_inline_markdown($line) . '</p>';
    }

    if ($inList) {
        $html .= '</ul>';
    }

    return $html;
}

function seo_render_breadcrumbs(array $items): string
{
    $html = '<nav class="dwseo__breadcrumbs" aria-label="Breadcrumb"><ol>';
    $last = count($items) - 1;

    foreach ($items as $i => $item) {
        $html .= '<li>';
        if ($i < $last) {
            $html .= '<a href="' . seo_e($item['url']) . '">' . seo_e($item['name']) . '</a>';
        } else {
            $html .= '<span aria-current="page">' . seo_e($item['name']) . '</span>';
        }
        $html .= '</li>';
    }

    $html .= '</ol></nav>';
    return $html;
}

function seo_whatsapp_url(array $base): string
{
    $wa = $base['informacion_contacto']['whatsapp'][0] ?? [];
    $number = preg_replace('/\D+/', '', (string)($wa['numero'] ?? ''));
    $msg = (string)($wa['mensaje'] ?? 'Hola, quiero informes');
    return $number !== '' ? 'https://wa.me/' . $number . '?text=' . rawurlencode($msg) : '#';
}

function seo_phone_url(array $base): string
{
    $phone = (string)($base['informacion_contacto']['telefono'][0] ?? '');
    $digits = preg_replace('/\D+/', '', $phone);
    return $digits !== '' ? 'tel:' . $digits : '#';
}

function seo_mail_url(array $base): string
{
    $mail = trim((string)($base['informacion_contacto']['correo'][0] ?? ''));
    return $mail !== '' ? 'mailto:' . $mail : '#';
}

function seo_404(): void
{
    http_response_code(404);
    echo '404';
    exit;
}

/**
 * Incluye un componente del sitio definido en base.json > seo_engine.
 *
 * Modos según la extensión del archivo en base.json:
 *
 *   .php   → require  (sitios con stack PHP: variables, funciones, etc.)
 *   .html  → readfile (sitios legacy en HTML estático: se vuelca tal cual)
 *   null / "" → silencio (modo standalone: la página SEO se auto-contiene)
 *
 * La ruta es relativa a la raíz del sitio (dirname(__DIR__) desde /seo-engine/).
 *
 * Ejemplo: seo_require_component($base, 'header')
 */
function seo_require_component(array $base, string $key): void
{
    $relative = trim((string)($base['seo_engine'][$key] ?? ''));
    if ($relative === '') {
        // null / vacío → modo standalone, no renderiza nada
        return;
    }

    // La raíz del sitio está un nivel por encima de /seo-engine/
    $path = dirname(__DIR__) . '/' . ltrim($relative, '/');

    if (!is_file($path)) {
        return;   // archivo no encontrado → silencio
    }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    if ($ext === 'html') {
        // Sitio legacy en HTML: vuelca el contenido directamente
        readfile($path);
    } else {
        // .php u otra extensión ejecutable → include normal
        require $path;
    }
}

function seo_render_page_v2(array $ctx): string
{
    $page    = $ctx['page'];
    $base    = $ctx['base'];
    $phUrl   = seo_phone_url($base);
    $mailUrl = seo_mail_url($base);

    // WhatsApp URL y textos estandarizados
    $waUrl         = seo_whatsapp_url($base);
    $ctaPrincipal  = 'Enviar WhatsApp Atención Segura';
    $ctaSecundario = 'Atención urgente por Llamada';
    $bloques        = $page['seccion_seo']['bloques']   ?? [];
    $intro          = (string)($page['seccion_seo']['introduccion']  ?? '');
    $faqs           = $page['faqs']                     ?? [];
    $beneficios     = $page['beneficios_contacto']      ?? [];
    $heroImage      = (string)($ctx['hero_image']       ?? '');
    $heroAlt        = (string)($ctx['hero_alt']         ?? '');

    ob_start();
    ?>
    <main class="dwseo">
        <div class="dwseo__wrap">

            <?= seo_render_breadcrumbs($ctx['breadcrumbs']) ?>

            <section class="dwseo__hero">
                <article class="dwseo__hero-card">
                    <h1><?= seo_e((string)($page['seo']['h1'] ?? $page['title'] ?? '')) ?></h1>

                    <?php if ($intro !== ''): ?>
                        <div class="dwseo__lead"><p><?= seo_e($intro) ?></p></div>
                    <?php endif; ?>

                    <div class="dwseo__actions">
                        <a class="dwseo__btn dwseo__btn--primary" href="<?= seo_e($waUrl) ?>">
                            <?= seo_e($ctaPrincipal) ?>
                        </a>
                        <a class="dwseo__btn dwseo__btn--ghost" href="<?= seo_e($phUrl) ?>">
                            <?= seo_e($ctaSecundario) ?>
                        </a>
                    </div>
                </article>

                <?php if ($heroImage !== ''): ?>
                    <figure class="dwseo__hero-figure">
                        <img src="<?= seo_e($heroImage) ?>"
                             alt="<?= seo_e($heroAlt) ?>"
                             width="600" height="400"
                             loading="eager"
                             decoding="async">
                    </figure>
                <?php endif; ?>
            </section>

            <div class="dwseo__grid">
                <article class="dwseo__main">

                    <?php foreach ($bloques as $bloque): ?>
                        <section class="dwseo__section">
                            <?php if (!empty($bloque['h2'])): ?>
                                <h2><?= seo_e((string)$bloque['h2']) ?></h2>
                            <?php endif; ?>

                            <?php if (!empty($bloque['descripcion'])): ?>
                                <p><?= seo_e((string)$bloque['descripcion']) ?></p>
                            <?php endif; ?>

                            <?php foreach (($bloque['h3'] ?? []) as $sub): ?>
                                <div class="dwseo__subblock">
                                    <?php if (!empty($sub['titulo'])): ?>
                                        <h3><?= seo_e((string)$sub['titulo']) ?></h3>
                                    <?php endif; ?>
                                    <?php if (!empty($sub['contenido'])): ?>
                                        <p><?= seo_e((string)$sub['contenido']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endforeach; ?>

                    <?php if (!empty($faqs)): ?>
                        <section class="dwseo__faq" aria-labelledby="dwseo-faq-title">
                            <h2 id="dwseo-faq-title">Preguntas frecuentes</h2>
                            <div class="dwseo__faq-list">
                                <?php foreach ($faqs as $faq): ?>
                                    <details class="dwseo__faq-item">
                                        <summary><?= seo_e((string)($faq['pregunta'] ?? '')) ?></summary>
                                        <div class="dwseo__faq-answer">
                                            <p><?= seo_e((string)($faq['respuesta'] ?? '')) ?></p>
                                        </div>
                                    </details>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($ctx['related'])): ?>
                        <section class="dwseo__section">
                            <h2><?= seo_e((string)($ctx['related_title'] ?? 'Temas relacionados')) ?></h2>
                            <div class="dwseo__related-grid">
                                <?php foreach ($ctx['related'] as $rel): ?>
                                    <a class="dwseo__related-card" href="<?= seo_e((string)$rel['url']) ?>">
                                        <strong><?= seo_e((string)$rel['title']) ?></strong>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                </article>

                <aside class="dwseo__aside" aria-label="Beneficios y contacto">

                    <?php if (!empty($beneficios)): ?>
                        <section class="dwseo__card">
                            <h2>Beneficios</h2>
                            <ul class="dwseo__checklist">
                                <?php foreach ($beneficios as $b): ?>
                                    <li><?= seo_e((string)$b) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <section class="dwseo__card">
                        <h2>Contacto</h2>
                        <address class="dwseo__address">
                            <p><a href="<?= seo_e($waUrl) ?>">WhatsApp</a></p>
                            <p><a href="<?= seo_e($phUrl) ?>"><?= seo_e((string)($base['informacion_contacto']['telefono'][0] ?? '')) ?></a></p>
                            <p><a href="<?= seo_e($mailUrl) ?>"><?= seo_e((string)($base['informacion_contacto']['correo'][0] ?? '')) ?></a></p>
                            <?php if (!empty($base['informacion_contacto']['horario'][0]['horas'])): ?>
                                <p><?= seo_e((string)$base['informacion_contacto']['horario'][0]['horas']) ?></p>
                            <?php endif; ?>
                        </address>
                    </section>

                </aside>
            </div>

        </div>
    </main>
    <?php
    return (string)ob_get_clean();
}

function seo_render_main(array $ctx): string
{
    $page = $ctx['page'];
    $base = $ctx['base'];
    $waUrl = seo_whatsapp_url($base);
    $phUrl = seo_phone_url($base);
    $mailUrl = seo_mail_url($base);

    ob_start();
    ?>
    <main class="dwseo">
        <div class="dwseo__wrap">

            <?= seo_render_breadcrumbs($ctx['breadcrumbs']) ?>

            <section class="dwseo__hero">
                <article class="dwseo__hero-card">
                    <?php if (!empty($page['hero']['eyebrow'])): ?>
                        <span class="dwseo__eyebrow"><?= seo_e((string)$page['hero']['eyebrow']) ?></span>
                    <?php endif; ?>

                    <h1><?= seo_e((string)($page['seo']['h1'] ?? $page['title'] ?? '')) ?></h1>

                    <?php if (!empty($page['intro_markdown'])): ?>
                        <div class="dwseo__lead">
                            <?= seo_markdown_to_html(seo_apply_interlinks_markdown((string)$page['intro_markdown'], $page['interlinks'] ?? [])) ?>
                        </div>
                    <?php endif; ?>

                    <div class="dwseo__actions">
                        <a class="dwseo__btn dwseo__btn--primary" href="<?= seo_e($waUrl) ?>">
                            <?= seo_e((string)($page['cta']['primary_label'] ?? 'Escríbenos por WhatsApp')) ?>
                        </a>
                        <a class="dwseo__btn dwseo__btn--ghost" href="<?= seo_e($phUrl) ?>">
                            <?= seo_e((string)($page['cta']['secondary_label'] ?? 'Llamar ahora')) ?>
                        </a>
                    </div>
                </article>

                <?php if (!empty($page['hero']['image'])): ?>
                    <figure class="dwseo__image-card">
                        <img
                            src="<?= seo_e((string)$page['hero']['image']) ?>"
                            alt="<?= seo_e((string)($page['hero']['image_alt'] ?? ($page['seo']['h1'] ?? ''))) ?>"
                            width="960"
                            height="960"
                            loading="eager"
                            decoding="async">
                    </figure>
                <?php endif; ?>
            </section>

            <div class="dwseo__grid">
                <article class="dwseo__main">
                    <?php foreach (($page['sections'] ?? []) as $section): ?>
                        <section class="dwseo__section">
                            <?php if (!empty($section['h2'])): ?>
                                <h2><?= seo_e((string)$section['h2']) ?></h2>
                            <?php endif; ?>

                            <?php if (!empty($section['markdown'])): ?>
                                <?= seo_markdown_to_html(seo_apply_interlinks_markdown((string)$section['markdown'], $page['interlinks'] ?? [])) ?>
                            <?php endif; ?>

                            <?php foreach (($section['items'] ?? []) as $item): ?>
                                <div class="dwseo__subblock">
                                    <?php if (!empty($item['h3'])): ?>
                                        <h3><?= seo_e((string)$item['h3']) ?></h3>
                                    <?php endif; ?>
                                    <?php if (!empty($item['markdown'])): ?>
                                        <?= seo_markdown_to_html(seo_apply_interlinks_markdown((string)$item['markdown'], $page['interlinks'] ?? [])) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endforeach; ?>

                    <?php if (!empty($page['faq'])): ?>
                        <section class="dwseo__faq" aria-labelledby="dwseo-faq-title">
                            <h2 id="dwseo-faq-title">Preguntas frecuentes</h2>
                            <div class="dwseo__faq-list">
                                <?php foreach ($page['faq'] as $faq): ?>
                                    <details class="dwseo__faq-item">
                                        <summary><?= seo_e((string)($faq['question'] ?? '')) ?></summary>
                                        <div class="dwseo__faq-answer">
                                            <?= seo_markdown_to_html((string)($faq['answer'] ?? '')) ?>
                                        </div>
                                    </details>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($ctx['related'])): ?>
                        <section class="dwseo__section">
                            <h2><?= seo_e((string)($ctx['related_title'] ?? 'Temas relacionados')) ?></h2>
                            <div class="dwseo__related-grid">
                                <?php foreach ($ctx['related'] as $rel): ?>
                                    <a class="dwseo__related-card" href="<?= seo_e((string)$rel['url']) ?>">
                                        <strong><?= seo_e((string)$rel['title']) ?></strong>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                </article>

                <aside class="dwseo__aside" aria-label="Contacto y beneficios">
                    <?php if (!empty($page['benefits'])): ?>
                        <section class="dwseo__card">
                            <h2>Beneficios</h2>
                            <ul class="dwseo__checklist">
                                <?php foreach ($page['benefits'] as $benefit): ?>
                                    <li><?= seo_e((string)$benefit) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <section class="dwseo__card">
                        <h2>Contacto</h2>
                        <address class="dwseo__address">
                            <p><a href="<?= seo_e($waUrl) ?>">WhatsApp</a></p>
                            <p><a href="<?= seo_e($phUrl) ?>"><?= seo_e((string)($base['informacion_contacto']['telefono'][0] ?? '')) ?></a></p>
                            <p><a href="<?= seo_e($mailUrl) ?>"><?= seo_e((string)($base['informacion_contacto']['correo'][0] ?? '')) ?></a></p>
                            <?php if (!empty($base['informacion_contacto']['horario'][0]['horas'])): ?>
                                <p><?= seo_e((string)$base['informacion_contacto']['horario'][0]['horas']) ?></p>
                            <?php endif; ?>
                        </address>
                    </section>
                </aside>
            </div>
        </div>
    </main>
    <?php
    return (string)ob_get_clean();
}
