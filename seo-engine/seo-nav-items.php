<?php
declare(strict_types=1);

/**
 * seo-nav-items.php
 *
 * Genera navegacion SEO reutilizable para sitios PHP/HTML.
 *
 * Formatos soportados:
 * - php-isolated   : dropdown aislado con CSS/JS propio
 * - php-modular    : salida legacy basada en <li>/<ul>
 * - html-bootstrap : salida legacy para navbars Bootstrap
 */

function seo_nav_load_site_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $path = __DIR__ . '/site-config.json';
    if (!is_file($path)) {
        $config = [];
        return $config;
    }

    $raw = json_decode((string)file_get_contents($path), true);
    $config = is_array($raw) ? $raw : [];

    return $config;
}

function seo_nav_site_path(): string
{
    $config = seo_nav_load_site_config();

    $configuredPath = trim((string)($config['seo_engine']['site_path'] ?? ''));
    if ($configuredPath !== '') {
        $configuredPath = '/' . trim($configuredPath, '/');
        return $configuredPath === '/' ? '' : rtrim($configuredPath, '/');
    }

    $configuredUrl = trim((string)($config['seo_engine']['site_url'] ?? ''));
    if ($configuredUrl !== '') {
        $parts = parse_url($configuredUrl);
        $path = trim((string)($parts['path'] ?? ''), '/');
        if ($path !== '') {
            return '/' . $path;
        }
    }

    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptName === '' || $scriptName === '.') {
        return '';
    }

    $baseDir = str_replace('\\', '/', dirname(dirname($scriptName)));
    if (in_array($baseDir, ['', '.', '/', '\\'], true)) {
        return '';
    }

    $path = trim($baseDir, '/');
    return $path !== '' ? '/' . $path : '';
}

function seo_nav_build_path(string $basePath, string $slug1, ?string $slug2 = null): string
{
    $basePath = $basePath !== '' ? '/' . trim($basePath, '/') : '';
    $path = $basePath . '/' . trim($slug1, '/');
    if ($slug2 !== null && $slug2 !== '') {
        $path .= '/' . trim($slug2, '/');
    }
    return rtrim($path, '/') . '/';
}

function seo_nav_href(string $slug1, ?string $slug2 = null): string
{
    return seo_nav_build_path(seo_nav_site_path(), $slug1, $slug2);
}

/**
 * Lee mapa-seo.json y devuelve HTML listo para insertar en un <ul>.
 *
 * @param string $mapPath Ruta absoluta a mapa-seo.json
 * @param string $format  php-isolated | php-modular | html-bootstrap
 * @param ?string $parentLabel Etiqueta del menu padre
 * @param array $renderOptions Opciones legacy para php-modular
 */
function seo_generate_nav_items(
    string $mapPath,
    string $format = 'php-modular',
    ?string $parentLabel = null,
    array $renderOptions = []
): string {
    if (!is_file($mapPath)) {
        return '';
    }

    $raw = json_decode((string)file_get_contents($mapPath), true);
    if (!is_array($raw) || empty($raw['seo']) || !is_array($raw['seo'])) {
        return '';
    }

    $slug = static function (string $text): string {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = mb_strtolower($text, 'UTF-8');
        $text = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $text
        );
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        return trim((string)$text, '-');
    };

    $slugToTitle = static function (string $value): string {
        return ucfirst(str_replace('-', ' ', $value));
    };

    $esc = static function (string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    };

    $entries = [];

    foreach ($raw['seo'] as $key => $value) {
        if (is_array($value) && isset($value['title'], $value['children'])) {
            $title1 = (string)$value['title'];
            $slug1 = $slug($title1) ?: $slug((string)$key);
            $children = (array)$value['children'];
        } elseif (is_array($value)) {
            $slug1 = $slug((string)$key);
            $title1 = ($slug((string)$key) === $key) ? $slugToTitle((string)$key) : (string)$key;
            $children = $value;
        } else {
            continue;
        }

        if ($slug1 === '' || $title1 === '') {
            continue;
        }

        $childItems = [];
        foreach ($children as $item) {
            if (is_string($item)) {
                $slug2 = $slug($item);
                $title2 = ($slug($item) === $item) ? $slugToTitle($item) : $item;
            } elseif (is_array($item) && !empty($item['title'])) {
                $slug2 = !empty($item['slug']) ? (string)$item['slug'] : $slug((string)$item['title']);
                $title2 = (string)$item['title'];
            } else {
                continue;
            }

            if ($slug2 === '' || $title2 === '') {
                continue;
            }

            $childItems[] = ['slug' => $slug2, 'title' => $title2];
        }

        $entries[] = [
            'slug' => $slug1,
            'title' => $title1,
            'children' => $childItems,
        ];
    }

    if ($entries === []) {
        return '';
    }

    $parentLabel = trim((string)$parentLabel);

    if ($format === 'php-isolated') {
        return seo_nav_php_isolated(
            $entries,
            $parentLabel !== '' ? $parentLabel : 'Temas SEO',
            $esc
        );
    }

    if ($format === 'html-bootstrap') {
        return seo_nav_html_bootstrap_items($entries, $parentLabel, $esc);
    }

    return seo_nav_php_modular_items($entries, $parentLabel, $esc, $renderOptions);
}

function seo_nav_assets_markup(): string
{
    return <<<'HTML'
<style data-dwseo-nav-assets>
.dwseo-nav{position:relative}
.dwseo-nav,
.dwseo-nav a,
.dwseo-nav button{font:inherit}
.dwseo-nav__trigger{display:inline-flex;align-items:center;gap:.45rem;color:inherit;background:none;border:0;padding:0;cursor:pointer;line-height:inherit;text-decoration:none}
.dwseo-nav__trigger:focus-visible,
.dwseo-nav__group-link:focus-visible,
.dwseo-nav__group-toggle:focus-visible,
.dwseo-nav__child-link:focus-visible{outline:2px solid currentColor;outline-offset:3px}
.dwseo-nav__caret{width:.5rem;height:.5rem;display:inline-block;border-right:1.8px solid currentColor;border-bottom:1.8px solid currentColor;transform:rotate(45deg);transition:transform .18s ease}
.dwseo-nav.is-open>.dwseo-nav__trigger .dwseo-nav__caret{transform:rotate(-135deg)}
.dwseo-nav__panel{position:absolute;top:100%;left:0;z-index:9999;min-width:min(430px,92vw);max-width:min(430px,92vw);max-height:min(72vh,720px);overflow:auto;margin-top:.65rem;padding:.9rem;background:var(--dwseo-nav-surface,#fff);border:1px solid rgba(15,23,42,.12);border-radius:16px;box-shadow:0 18px 52px rgba(15,23,42,.16);color:var(--dwseo-nav-fg,currentColor)}
.dwseo-nav__panel[hidden]{display:none!important}
.dwseo-nav__groups{list-style:none;margin:0;padding:0;display:grid;gap:.45rem}
.dwseo-nav__group{list-style:none;margin:0;padding:0;border-bottom:1px solid rgba(15,23,42,.08)}
.dwseo-nav__group:last-child{border-bottom:0}
.dwseo-nav__group-row{display:flex;align-items:center;gap:.65rem;justify-content:space-between;padding:.1rem 0}
.dwseo-nav__group-link{display:block;flex:1 1 auto;min-width:0;padding:.35rem 0;color:inherit;font-weight:600;line-height:1.35;text-decoration:none}
.dwseo-nav__group-toggle{display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;width:2rem;height:2rem;margin:0;padding:0;border:0;background:none;color:inherit;cursor:pointer}
.dwseo-nav__group.is-open .dwseo-nav__group-toggle .dwseo-nav__caret{transform:rotate(-135deg)}
.dwseo-nav__children{list-style:none;margin:0;padding:0 0 .5rem 0;display:grid;gap:.2rem}
.dwseo-nav__children[hidden]{display:none!important}
.dwseo-nav__children li{margin:0;padding:0}
.dwseo-nav__child-link{display:block;padding:.34rem 0 .34rem .85rem;color:inherit;line-height:1.42;text-decoration:none;opacity:.88}
.dwseo-nav__trigger:hover,.dwseo-nav__group-link:hover,.dwseo-nav__child-link:hover{text-decoration:underline;opacity:1}
@media (max-width: 991px){
  .dwseo-nav{width:100%}
  .dwseo-nav__trigger,
  .dwseo-nav__group-link,
  .dwseo-nav__group-toggle,
  .dwseo-nav__child-link,
  .dwseo-nav__panel{color:var(--dwseo-nav-mobile-fg,var(--dwseo-nav-fg,currentColor))}
  .dwseo-nav__panel{position:static;min-width:0;max-width:none;max-height:none;margin-top:.45rem;padding:.65rem 0 0;background:var(--dwseo-nav-mobile-surface,var(--dwseo-nav-surface,transparent));border:0;box-shadow:none}
  .dwseo-nav__group{border-color:rgba(127,127,127,.18)}
  .dwseo-nav__child-link{padding-left:.95rem}
}
.mobile-nav__container .dwseo-nav,
.mobile-nav__container .dwseo-nav__panel{width:100%}
.dwseo-nav__sr{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}
</style>
<script data-dwseo-nav-assets>
(function(){
  if(window.__dwseoNavInit){return;}
  window.__dwseoNavInit=true;

  function parseColor(value){
    if(!value){return null;}
    var match=value.match(/rgba?\(([^)]+)\)/i);
    if(!match){return null;}
    var parts=match[1].split(',').map(function(item){return parseFloat(item.trim());});
    if(parts.length<3){return null;}
    return {
      r: parts[0],
      g: parts[1],
      b: parts[2],
      a: parts.length>3 ? parts[3] : 1
    };
  }

  function colorToString(color){
    return 'rgb(' + Math.round(color.r) + ', ' + Math.round(color.g) + ', ' + Math.round(color.b) + ')';
  }

  function luminance(color){
    function channel(v){
      v=v/255;
      return v<=0.03928 ? v/12.92 : Math.pow((v+0.055)/1.055,2.4);
    }
    return 0.2126*channel(color.r)+0.7152*channel(color.g)+0.0722*channel(color.b);
  }

  function contrastRatio(c1,c2){
    var l1=luminance(c1);
    var l2=luminance(c2);
    var lighter=Math.max(l1,l2);
    var darker=Math.min(l1,l2);
    return (lighter+0.05)/(darker+0.05);
  }

  function firstSolidBackground(node){
    var current=node;
    while(current && current!==document.documentElement){
      var style=window.getComputedStyle(current);
      var color=parseColor(style.backgroundColor);
      if(color && color.a!==0){return color;}
      current=current.parentElement;
    }
    return {r:255,g:255,b:255,a:1};
  }

  function syncMobileContrast(root){
    if(!root){return;}
    var mobileContext=root.closest('.mobile-nav__container, .mobile-nav__content, .mobile-nav__wrapper');
    if(!mobileContext){
      root.style.removeProperty('--dwseo-nav-mobile-fg');
      root.style.removeProperty('--dwseo-nav-mobile-surface');
      return;
    }

    var bg=firstSolidBackground(mobileContext);
    var current=parseColor(window.getComputedStyle(root).color) || {r:255,g:255,b:255,a:1};
    var light={r:255,g:255,b:255,a:1};
    var dark={r:17,g:24,b:39,a:1};
    var currentRatio=contrastRatio(current,bg);
    var target=currentRatio>=4.5 ? current : (luminance(bg) > 0.55 ? dark : light);

    root.style.setProperty('--dwseo-nav-mobile-fg', colorToString(target));
    root.style.setProperty('--dwseo-nav-mobile-surface', 'transparent');
  }

  function closeNav(root){
    if(!root){return;}
    root.classList.remove('is-open');
    var trigger=root.querySelector('.dwseo-nav__trigger');
    var panel=root.querySelector('.dwseo-nav__panel');
    if(trigger){trigger.setAttribute('aria-expanded','false');}
    if(panel){panel.hidden=true;}
  }

  function openNav(root){
    if(!root){return;}
    var trigger=root.querySelector('.dwseo-nav__trigger');
    var panel=root.querySelector('.dwseo-nav__panel');
    if(trigger){trigger.setAttribute('aria-expanded','true');}
    if(panel){panel.hidden=false;}
    root.classList.add('is-open');
  }

  function closeOtherNavs(current){
    document.querySelectorAll('.dwseo-nav.is-open').forEach(function(root){
      if(root!==current){closeNav(root);}
    });
  }

  function closeSiblingGroups(group){
    var parent=group && group.parentElement;
    if(!parent){return;}
    parent.querySelectorAll('.dwseo-nav__group.is-open').forEach(function(item){
      if(item!==group){closeGroup(item);}
    });
  }

  function closeGroup(group){
    if(!group){return;}
    group.classList.remove('is-open');
    var button=group.querySelector('.dwseo-nav__group-toggle');
    var list=group.querySelector('.dwseo-nav__children');
    if(button){button.setAttribute('aria-expanded','false');}
    if(list){list.hidden=true;}
  }

  function openGroup(group){
    if(!group){return;}
    closeSiblingGroups(group);
    group.classList.add('is-open');
    var button=group.querySelector('.dwseo-nav__group-toggle');
    var list=group.querySelector('.dwseo-nav__children');
    if(button){button.setAttribute('aria-expanded','true');}
    if(list){list.hidden=false;}
  }

  function syncAllMobileContrast(){
    document.querySelectorAll('.dwseo-nav').forEach(syncMobileContrast);
  }

  document.addEventListener('click',function(event){
    var trigger=event.target.closest('.dwseo-nav__trigger');
    if(trigger){
      event.preventDefault();
      var root=trigger.closest('.dwseo-nav');
      if(!root){return;}
      syncMobileContrast(root);
      if(root.classList.contains('is-open')){
        closeNav(root);
      }else{
        closeOtherNavs(root);
        openNav(root);
      }
      return;
    }

    var groupToggle=event.target.closest('.dwseo-nav__group-toggle');
    if(groupToggle){
      event.preventDefault();
      var group=groupToggle.closest('.dwseo-nav__group');
      if(!group){return;}
      if(group.classList.contains('is-open')){
        closeGroup(group);
      }else{
        openGroup(group);
      }
      return;
    }

    document.querySelectorAll('.dwseo-nav.is-open').forEach(function(root){
      if(!root.contains(event.target)){closeNav(root);}
    });
  });

  document.addEventListener('keydown',function(event){
    if(event.key!=='Escape'){return;}
    document.querySelectorAll('.dwseo-nav.is-open').forEach(closeNav);
    document.querySelectorAll('.dwseo-nav__group.is-open').forEach(closeGroup);
  });

  window.addEventListener('resize',syncAllMobileContrast);
  document.addEventListener('DOMContentLoaded',syncAllMobileContrast);
  syncAllMobileContrast();
})();
</script>
HTML;
}

function seo_nav_php_isolated(array $entries, string $parentLabel, callable $esc): string
{
    $html  = '    <li class="dwseo-nav" data-dwseo-nav>' . "\n";
        $html .= '        <a href="#" class="dwseo-nav__trigger" aria-haspopup="true" aria-expanded="false">' . "\n";
    $html .= '            <span>' . $esc($parentLabel) . '</span>' . "\n";
    $html .= '            <span class="dwseo-nav__caret" aria-hidden="true"></span>' . "\n";
    $html .= '        </a>' . "\n";
    $html .= '        <div class="dwseo-nav__panel" hidden>' . "\n";
    $html .= '            <ul class="dwseo-nav__groups">' . "\n";

    foreach ($entries as $entry) {
        $html .= '                <li class="dwseo-nav__group">' . "\n";
        $html .= '                    <div class="dwseo-nav__group-row">' . "\n";
        $html .= '                        <a class="dwseo-nav__group-link" href="' . $esc(seo_nav_href($entry['slug'])) . '">'
            . $esc($entry['title']) . '</a>' . "\n";

        if (!empty($entry['children'])) {
            $groupId = 'dwseo-nav-' . md5((string)$entry['slug']);
            $html .= '                        <button type="button" class="dwseo-nav__group-toggle" aria-expanded="false" aria-controls="'
                . $esc($groupId) . '">' . "\n";
            $html .= '                            <span class="dwseo-nav__sr">Ver subsecciones de ' . $esc($entry['title']) . '</span>' . "\n";
            $html .= '                            <span class="dwseo-nav__caret" aria-hidden="true"></span>' . "\n";
            $html .= '                        </button>' . "\n";
            $html .= '                    </div>' . "\n";
            $html .= '                    <ul class="dwseo-nav__children" id="' . $esc($groupId) . '" hidden>' . "\n";
            foreach ($entry['children'] as $child) {
                $href = $esc(seo_nav_href($entry['slug'], $child['slug']));
                $html .= '                        <li><a class="dwseo-nav__child-link" href="' . $href . '">'
                    . $esc($child['title']) . '</a></li>' . "\n";
            }
            $html .= '                    </ul>' . "\n";
        } else {
            $html .= '                    </div>' . "\n";
        }

        $html .= '                </li>' . "\n";
    }

    $html .= '            </ul>' . "\n";
    $html .= '        </div>' . "\n";
    $html .= '    </li>' . "\n";

    return $html;
}

function seo_nav_php_modular_items(array $entries, string $parentLabel, callable $esc, array $renderOptions = []): string
{
    $items = '';
    foreach ($entries as $entry) {
        $items .= seo_nav_php_modular(
            $entry['slug'],
            $entry['title'],
            $entry['children'],
            $esc,
            $renderOptions
        );
    }

    if ($items === '') {
        return '';
    }

    if ($parentLabel === '') {
        return $items;
    }

    return seo_nav_wrap_parent_label($items, 'php-modular', $parentLabel, $esc, $renderOptions);
}

function seo_nav_html_bootstrap_items(array $entries, string $parentLabel, callable $esc): string
{
    $items = '';
    foreach ($entries as $entry) {
        $items .= seo_nav_html_bootstrap($entry['slug'], $entry['title'], $entry['children'], $esc);
    }

    if ($items === '') {
        return '';
    }

    if ($parentLabel === '') {
        return $items;
    }

    return seo_nav_wrap_parent_label($items, 'html-bootstrap', $parentLabel, $esc);
}

function seo_nav_php_modular(string $slug1, string $title1, array $children, callable $esc, array $renderOptions = []): string
{
    $simpleLiAttrs = seo_nav_attr_fragment($renderOptions['simple_li_attrs'] ?? '');
    $simpleLinkAttrs = seo_nav_attr_fragment($renderOptions['simple_link_attrs'] ?? '');
    $dropdownLiAttrs = seo_nav_attr_fragment($renderOptions['dropdown_li_attrs'] ?? 'class="dropdown"');
    $dropdownLinkAttrs = seo_nav_attr_fragment($renderOptions['dropdown_link_attrs'] ?? '');
    $submenuUlAttrs = seo_nav_attr_fragment($renderOptions['submenu_ul_attrs'] ?? '');
    $submenuLiAttrs = seo_nav_attr_fragment($renderOptions['submenu_li_attrs'] ?? '');
    $submenuLinkAttrs = seo_nav_attr_fragment($renderOptions['submenu_link_attrs'] ?? '');

    if (empty($children)) {
        if ($simpleLiAttrs === '') {
            $simpleLiAttrs = $submenuLiAttrs;
        }
        if ($simpleLinkAttrs === '') {
            $simpleLinkAttrs = $submenuLinkAttrs;
        }
        return '    <li' . $simpleLiAttrs . '><a href="' . $esc(seo_nav_href($slug1)) . '"' . $simpleLinkAttrs . '>'
            . $esc($title1) . '</a></li>' . "\n";
    }

    $out  = '    <li' . $dropdownLiAttrs . '>' . "\n";
    $out .= '        <a href="' . $esc(seo_nav_href($slug1)) . '"' . $dropdownLinkAttrs . '>'
        . $esc($title1) . '</a>' . "\n";
    $out .= '        <ul' . $submenuUlAttrs . '>' . "\n";

    foreach ($children as $child) {
        $href = $esc(seo_nav_href($slug1, (string)$child['slug']));
        $out .= '            <li' . $submenuLiAttrs . '><a href="' . $href . '"' . $submenuLinkAttrs . '>'
            . $esc((string)$child['title']) . '</a></li>' . "\n";
    }

    $out .= '        </ul>' . "\n";
    $out .= '    </li>' . "\n";

    return $out;
}

function seo_nav_html_bootstrap(string $slug1, string $title1, array $children, callable $esc): string
{
    if (empty($children)) {
        return '                <li class="nav-item"><a href="' . $esc(seo_nav_href($slug1)) . '" class="nav-link">'
            . $esc($title1) . '</a></li>' . "\n";
    }

    $out  = '                <li class="nav-item">' . "\n";
    $out .= '                  <a href="' . $esc(seo_nav_href($slug1)) . '" class="nav-link dropdown-toggle">'
        . $esc($title1) . ' <i class="fas fa-angle-down"></i></a>' . "\n";
    $out .= '                  <ul class="dropdown-menu">' . "\n";

    foreach ($children as $child) {
        $href = $esc(seo_nav_href($slug1, (string)$child['slug']));
        $out .= '                    <li class="nav-item"><a href="' . $href . '" class="nav-link">'
            . $esc((string)$child['title']) . '</a></li>' . "\n";
    }

    $out .= '                  </ul>' . "\n";
    $out .= '                </li>' . "\n";

    return $out;
}

function seo_nav_wrap_parent_label(string $itemsHtml, string $format, string $parentLabel, callable $esc, array $renderOptions = []): string
{
    $parentEsc = $esc($parentLabel);

    if ($format === 'html-bootstrap') {
        $html  = '                <li class="nav-item dropdown">' . "\n";
        $html .= '                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">'
            . $parentEsc . ' <i class="fas fa-angle-down"></i></a>' . "\n";
        $html .= '                  <ul class="dropdown-menu">' . "\n";
        $html .= $itemsHtml;
        $html .= '                  </ul>' . "\n";
        $html .= '                </li>' . "\n";
        return $html;
    }

    $dropdownLiAttrs = seo_nav_attr_fragment($renderOptions['dropdown_li_attrs'] ?? 'class="dropdown"');
    $dropdownLinkAttrs = seo_nav_attr_fragment($renderOptions['dropdown_link_attrs'] ?? '');
    $submenuUlAttrs = seo_nav_attr_fragment($renderOptions['submenu_ul_attrs'] ?? '');

    $html  = '    <li' . $dropdownLiAttrs . '>' . "\n";
    $html .= '        <a href="#"' . $dropdownLinkAttrs . '>' . $parentEsc . '</a>' . "\n";
    $html .= '        <ul' . $submenuUlAttrs . '>' . "\n";
    $html .= $itemsHtml;
    $html .= '        </ul>' . "\n";
    $html .= '    </li>' . "\n";

    return $html;
}

function seo_nav_attr_fragment(string $attrs): string
{
    $attrs = trim($attrs);
    return $attrs !== '' ? ' ' . $attrs : '';
}
