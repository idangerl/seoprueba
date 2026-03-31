/**
 * seo-nav-init.js
 *
 * Inicializa el nav SEO aislado para deployments tipo HTML estático.
 * Lee mapa-seo.json y site-config.json desde el directorio del engine
 * y construye el mismo nav aislado (dwseo-nav) que el adapter PHP.
 *
 * Uso: <script defer src="/seo-engine/seo-nav-init.js" data-seo-nav-label="Servicios SEO"></script>
 */
(function () {
  'use strict';

  // -----------------------------------------------------------------------
  // CONFIGURACION
  // -----------------------------------------------------------------------

  var _scriptEl = document.currentScript;
  if (!_scriptEl) {
    var _scripts = document.querySelectorAll('script[src]');
    for (var _i = _scripts.length - 1; _i >= 0; _i--) {
      if (_scripts[_i].src.indexOf('seo-nav-init') !== -1) {
        _scriptEl = _scripts[_i];
        break;
      }
    }
  }

  var _engineBase = _scriptEl
    ? _scriptEl.src.replace(/\/[^/]+$/, '/')
    : (window.location.origin + '/seo-engine/');

  var _navLabel = (_scriptEl && _scriptEl.getAttribute('data-seo-nav-label')) || 'Temas SEO';

  // -----------------------------------------------------------------------
  // UTILIDADES
  // -----------------------------------------------------------------------

  function _slugify(text) {
    var map = { '\u00e1': 'a', '\u00e9': 'e', '\u00ed': 'i', '\u00f3': 'o', '\u00fa': 'u', '\u00fc': 'u', '\u00f1': 'n' };
    return String(text)
      .toLowerCase()
      .replace(/[\u00e1\u00e9\u00ed\u00f3\u00fa\u00fc\u00f1]/g, function (c) { return map[c] || c; })
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  function _slugToTitle(slug) {
    return slug.charAt(0).toUpperCase() + slug.slice(1).replace(/-/g, ' ');
  }

  function _esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function _href(sitePath, slug1, slug2) {
    sitePath = sitePath ? '/' + sitePath.replace(/^\/+|\/+$/g, '') : '';
    if (sitePath === '/') sitePath = '';
    var p = sitePath + '/' + slug1.replace(/^\/+|\/+$/g, '');
    if (slug2) p += '/' + String(slug2).replace(/^\/+|\/+$/g, '');
    return p.replace(/\/+$/, '') + '/';
  }

  // -----------------------------------------------------------------------
  // CSS ASSETS (identico a seo_nav_assets_markup en seo-nav-items.php)
  // -----------------------------------------------------------------------

  function _injectCSS() {
    if (document.querySelector('[data-dwseo-nav-assets]')) return;
    var style = document.createElement('style');
    style.setAttribute('data-dwseo-nav-assets', '');
    style.textContent = [
      '.dwseo-nav{position:relative}',
      '.dwseo-nav,.dwseo-nav a,.dwseo-nav button{font:inherit}',
      '.dwseo-nav__trigger{display:inline-flex;align-items:center;gap:.45rem;color:inherit;background:none;border:0;padding:0;cursor:pointer;line-height:inherit;text-decoration:none}',
      '.dwseo-nav__trigger:focus-visible,.dwseo-nav__group-link:focus-visible,.dwseo-nav__group-toggle:focus-visible,.dwseo-nav__child-link:focus-visible{outline:2px solid currentColor;outline-offset:3px}',
      '.dwseo-nav__caret{width:.5rem;height:.5rem;display:inline-block;border-right:1.8px solid currentColor;border-bottom:1.8px solid currentColor;transform:rotate(45deg);transition:transform .18s ease}',
      '.dwseo-nav.is-open>.dwseo-nav__trigger .dwseo-nav__caret{transform:rotate(-135deg)}',
      '.dwseo-nav__panel{position:absolute;top:100%;left:0;z-index:9999;min-width:min(430px,92vw);max-width:min(430px,92vw);max-height:min(72vh,720px);overflow:auto;margin-top:.65rem;padding:.9rem;background:var(--dwseo-nav-surface,#fff);border:1px solid rgba(15,23,42,.12);border-radius:16px;box-shadow:0 18px 52px rgba(15,23,42,.16);color:var(--dwseo-nav-fg,currentColor)}',
      '.dwseo-nav__panel[hidden]{display:none!important}',
      '.dwseo-nav__groups{list-style:none;margin:0;padding:0;display:grid;gap:.45rem}',
      '.dwseo-nav__group{list-style:none;margin:0;padding:0;border-bottom:1px solid rgba(15,23,42,.08)}',
      '.dwseo-nav__group:last-child{border-bottom:0}',
      '.dwseo-nav__group-row{display:flex;align-items:center;gap:.65rem;justify-content:space-between;padding:.1rem 0}',
      '.dwseo-nav__group-link{display:block;flex:1 1 auto;min-width:0;padding:.35rem 0;color:inherit;font-weight:600;line-height:1.35;text-decoration:none}',
      '.dwseo-nav__group-toggle{display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;width:2rem;height:2rem;margin:0;padding:0;border:0;background:none;color:inherit;cursor:pointer}',
      '.dwseo-nav__group.is-open .dwseo-nav__group-toggle .dwseo-nav__caret{transform:rotate(-135deg)}',
      '.dwseo-nav__children{list-style:none;margin:0;padding:0 0 .5rem 0;display:grid;gap:.2rem}',
      '.dwseo-nav__children[hidden]{display:none!important}',
      '.dwseo-nav__children li{margin:0;padding:0}',
      '.dwseo-nav__child-link{display:block;padding:.34rem 0 .34rem .85rem;color:inherit;line-height:1.42;text-decoration:none;opacity:.88}',
      '.dwseo-nav__trigger:hover,.dwseo-nav__group-link:hover,.dwseo-nav__child-link:hover{text-decoration:underline;opacity:1}',
      '@media (max-width: 991px){',
      '  .dwseo-nav{width:100%}',
      '  .dwseo-nav__trigger,.dwseo-nav__group-link,.dwseo-nav__group-toggle,.dwseo-nav__child-link,.dwseo-nav__panel{color:var(--dwseo-nav-mobile-fg,var(--dwseo-nav-fg,currentColor))}',
      '  .dwseo-nav__panel{position:static;min-width:0;max-width:none;max-height:none;margin-top:.45rem;padding:.65rem 0 0;background:var(--dwseo-nav-mobile-surface,var(--dwseo-nav-surface,transparent));border:0;box-shadow:none}',
      '  .dwseo-nav__group{border-color:rgba(127,127,127,.18)}',
      '  .dwseo-nav__child-link{padding-left:.95rem}',
      '}',
      '.mobile-nav__container .dwseo-nav,.mobile-nav__container .dwseo-nav__panel{width:100%}',
      '.dwseo-nav__sr{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}',
    ].join('\n');
    (document.head || document.documentElement).appendChild(style);
  }

  // -----------------------------------------------------------------------
  // COMPORTAMIENTO INTERACTIVO (identico al <script> de seo_nav_assets_markup)
  // -----------------------------------------------------------------------

  function _initBehavior() {
    if (window.__dwseoNavInit) return;
    window.__dwseoNavInit = true;

    function parseColor(value) {
      if (!value) return null;
      var match = value.match(/rgba?\(([^)]+)\)/i);
      if (!match) return null;
      var parts = match[1].split(',').map(function (item) { return parseFloat(item.trim()); });
      if (parts.length < 3) return null;
      return { r: parts[0], g: parts[1], b: parts[2], a: parts.length > 3 ? parts[3] : 1 };
    }

    function colorToString(color) {
      return 'rgb(' + Math.round(color.r) + ', ' + Math.round(color.g) + ', ' + Math.round(color.b) + ')';
    }

    function luminance(color) {
      function channel(v) {
        v = v / 255;
        return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
      }
      return 0.2126 * channel(color.r) + 0.7152 * channel(color.g) + 0.0722 * channel(color.b);
    }

    function contrastRatio(c1, c2) {
      var l1 = luminance(c1);
      var l2 = luminance(c2);
      var lighter = Math.max(l1, l2);
      var darker = Math.min(l1, l2);
      return (lighter + 0.05) / (darker + 0.05);
    }

    function firstSolidBackground(node) {
      var current = node;
      while (current && current !== document.documentElement) {
        var style = window.getComputedStyle(current);
        var color = parseColor(style.backgroundColor);
        if (color && color.a !== 0) return color;
        current = current.parentElement;
      }
      return { r: 255, g: 255, b: 255, a: 1 };
    }

    function syncMobileContrast(root) {
      if (!root) return;
      var mobileContext = root.closest('.mobile-nav__container, .mobile-nav__content, .mobile-nav__wrapper');
      if (!mobileContext) {
        root.style.removeProperty('--dwseo-nav-mobile-fg');
        root.style.removeProperty('--dwseo-nav-mobile-surface');
        return;
      }
      var bg = firstSolidBackground(mobileContext);
      var current = parseColor(window.getComputedStyle(root).color) || { r: 255, g: 255, b: 255, a: 1 };
      var light = { r: 255, g: 255, b: 255, a: 1 };
      var dark = { r: 17, g: 24, b: 39, a: 1 };
      var currentRatio = contrastRatio(current, bg);
      var target = currentRatio >= 4.5 ? current : (luminance(bg) > 0.55 ? dark : light);
      root.style.setProperty('--dwseo-nav-mobile-fg', colorToString(target));
      root.style.setProperty('--dwseo-nav-mobile-surface', 'transparent');
    }

    function closeNav(root) {
      if (!root) return;
      root.classList.remove('is-open');
      var trigger = root.querySelector('.dwseo-nav__trigger');
      var panel = root.querySelector('.dwseo-nav__panel');
      if (trigger) trigger.setAttribute('aria-expanded', 'false');
      if (panel) panel.hidden = true;
    }

    function openNav(root) {
      if (!root) return;
      var trigger = root.querySelector('.dwseo-nav__trigger');
      var panel = root.querySelector('.dwseo-nav__panel');
      if (trigger) trigger.setAttribute('aria-expanded', 'true');
      if (panel) panel.hidden = false;
      root.classList.add('is-open');
    }

    function closeOtherNavs(current) {
      document.querySelectorAll('.dwseo-nav.is-open').forEach(function (root) {
        if (root !== current) closeNav(root);
      });
    }

    function closeSiblingGroups(group) {
      var parent = group && group.parentElement;
      if (!parent) return;
      parent.querySelectorAll('.dwseo-nav__group.is-open').forEach(function (item) {
        if (item !== group) closeGroup(item);
      });
    }

    function closeGroup(group) {
      if (!group) return;
      group.classList.remove('is-open');
      var button = group.querySelector('.dwseo-nav__group-toggle');
      var list = group.querySelector('.dwseo-nav__children');
      if (button) button.setAttribute('aria-expanded', 'false');
      if (list) list.hidden = true;
    }

    function openGroup(group) {
      if (!group) return;
      closeSiblingGroups(group);
      group.classList.add('is-open');
      var button = group.querySelector('.dwseo-nav__group-toggle');
      var list = group.querySelector('.dwseo-nav__children');
      if (button) button.setAttribute('aria-expanded', 'true');
      if (list) list.hidden = false;
    }

    function syncAllMobileContrast() {
      document.querySelectorAll('.dwseo-nav').forEach(syncMobileContrast);
    }

    document.addEventListener('click', function (event) {
      var trigger = event.target.closest('.dwseo-nav__trigger');
      if (trigger) {
        event.preventDefault();
        var root = trigger.closest('.dwseo-nav');
        if (!root) return;
        syncMobileContrast(root);
        if (root.classList.contains('is-open')) {
          closeNav(root);
        } else {
          closeOtherNavs(root);
          openNav(root);
        }
        return;
      }

      var groupToggle = event.target.closest('.dwseo-nav__group-toggle');
      if (groupToggle) {
        event.preventDefault();
        var group = groupToggle.closest('.dwseo-nav__group');
        if (!group) return;
        if (group.classList.contains('is-open')) {
          closeGroup(group);
        } else {
          openGroup(group);
        }
        return;
      }

      document.querySelectorAll('.dwseo-nav.is-open').forEach(function (root) {
        if (!root.contains(event.target)) closeNav(root);
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      document.querySelectorAll('.dwseo-nav.is-open').forEach(closeNav);
      document.querySelectorAll('.dwseo-nav__group.is-open').forEach(closeGroup);
    });

    window.addEventListener('resize', syncAllMobileContrast);
    document.addEventListener('DOMContentLoaded', syncAllMobileContrast);
    syncAllMobileContrast();
  }

  // -----------------------------------------------------------------------
  // PARSE MAPA-SEO (logica identica a seo_generate_nav_items en PHP)
  // -----------------------------------------------------------------------

  function _parseEntries(seoMap) {
    var entries = [];
    Object.keys(seoMap).forEach(function (key) {
      var value = seoMap[key];
      var slug1, title1, children;

      if (value && typeof value === 'object' && !Array.isArray(value) && value.title && value.children) {
        title1 = String(value.title);
        slug1 = _slugify(title1) || _slugify(String(key));
        children = Array.isArray(value.children) ? value.children : [];
      } else if (Array.isArray(value)) {
        slug1 = _slugify(String(key));
        title1 = (_slugify(String(key)) === String(key)) ? _slugToTitle(String(key)) : String(key);
        children = value;
      } else {
        return;
      }

      if (!slug1 || !title1) return;

      var childItems = [];
      children.forEach(function (item) {
        var slug2, title2;
        if (typeof item === 'string') {
          slug2 = _slugify(item);
          title2 = (_slugify(item) === item) ? _slugToTitle(item) : item;
        } else if (item && item.title) {
          slug2 = item.slug || _slugify(String(item.title));
          title2 = String(item.title);
        } else {
          return;
        }
        if (slug2 && title2) childItems.push({ slug: slug2, title: title2 });
      });

      entries.push({ slug: slug1, title: title1, children: childItems });
    });
    return entries;
  }

  // -----------------------------------------------------------------------
  // BUILD NAV HTML (estructura identica a seo_nav_php_isolated en PHP)
  // -----------------------------------------------------------------------

  function _buildNavHTML(entries, sitePath) {
    var html = '';
    html += '    <li class="dwseo-nav" data-dwseo-nav>\n';
    html += '        <a href="#" class="dwseo-nav__trigger" aria-haspopup="true" aria-expanded="false">\n';
    html += '            <span>' + _esc(_navLabel) + '</span>\n';
    html += '            <span class="dwseo-nav__caret" aria-hidden="true"></span>\n';
    html += '        </a>\n';
    html += '        <div class="dwseo-nav__panel" hidden>\n';
    html += '            <ul class="dwseo-nav__groups">\n';

    entries.forEach(function (entry) {
      html += '                <li class="dwseo-nav__group">\n';
      html += '                    <div class="dwseo-nav__group-row">\n';
      html += '                        <a class="dwseo-nav__group-link" href="' + _esc(_href(sitePath, entry.slug)) + '">' + _esc(entry.title) + '</a>\n';

      if (entry.children && entry.children.length > 0) {
        var groupId = 'dwseo-nav-' + entry.slug;
        html += '                        <button type="button" class="dwseo-nav__group-toggle" aria-expanded="false" aria-controls="' + _esc(groupId) + '">\n';
        html += '                            <span class="dwseo-nav__sr">Ver subsecciones de ' + _esc(entry.title) + '</span>\n';
        html += '                            <span class="dwseo-nav__caret" aria-hidden="true"></span>\n';
        html += '                        </button>\n';
        html += '                    </div>\n';
        html += '                    <ul class="dwseo-nav__children" id="' + _esc(groupId) + '" hidden>\n';
        entry.children.forEach(function (child) {
          html += '                        <li><a class="dwseo-nav__child-link" href="' + _esc(_href(sitePath, entry.slug, child.slug)) + '">' + _esc(child.title) + '</a></li>\n';
        });
        html += '                    </ul>\n';
      } else {
        html += '                    </div>\n';
      }

      html += '                </li>\n';
    });

    html += '            </ul>\n';
    html += '        </div>\n';
    html += '    </li>\n';
    return html;
  }

  // -----------------------------------------------------------------------
  // INYECCION EN EL DOM
  // -----------------------------------------------------------------------

  /**
   * Devuelve el <ul> principal de cada <nav> encontrado en la página.
   * Estructura esperada: nav > ul > li (escritorio y móvil por separado).
   * Para cada nav toma el primer <ul> hijo directo; si no existe, el <ul>
   * con más <li> directos dentro de ese nav.
   */
  function _findTargetULs() {
    var results = [];
    document.querySelectorAll('nav').forEach(function (nav) {
      // Primer <ul> hijo directo del <nav>
      var ul = nav.querySelector(':scope > ul');
      if (!ul) {
        // Fallback: el <ul> con más <li> directos dentro del nav
        var best = null;
        var bestCount = 0;
        nav.querySelectorAll('ul').forEach(function (candidate) {
          var count = candidate.querySelectorAll(':scope > li').length;
          if (count > bestCount) { bestCount = count; best = candidate; }
        });
        ul = best;
      }
      if (ul) results.push(ul);
    });
    return results;
  }

  function _injectIntoUL(ul, navHTML) {
    // Evitar doble inyeccion en este ul
    if (ul.querySelector('.dwseo-nav')) return;

    var tmp = document.createElement('ul');
    tmp.innerHTML = navHTML;
    var navLi = tmp.firstElementChild;
    if (!navLi) return;

    // Insertar despues del 2do <li> top-level (mismo comportamiento que PHP)
    var liItems = Array.from(ul.querySelectorAll(':scope > li'));
    if (liItems.length >= 2) {
      liItems[1].insertAdjacentElement('afterend', navLi);
    } else if (liItems.length > 0) {
      liItems[liItems.length - 1].insertAdjacentElement('afterend', navLi);
    } else {
      ul.appendChild(navLi);
    }
  }

  function _injectIntoDOM(navHTML) {
    var targets = _findTargetULs();
    targets.forEach(function (ul) {
      _injectIntoUL(ul, navHTML);
    });
  }

  // -----------------------------------------------------------------------
  // MAIN
  // -----------------------------------------------------------------------

  function _init() {
    Promise.all([
      fetch(_engineBase + 'site-config.json').then(function (r) { return r.json(); }).catch(function () { return {}; }),
      fetch(_engineBase + 'mapa-seo.json').then(function (r) { return r.json(); }).catch(function () { return {}; }),
    ]).then(function (results) {
      var siteConfig = results[0] || {};
      var mapaData   = results[1] || {};

      // Resolver site_path (logica identica a seo_nav_site_path() en PHP)
      var sitePath = '';
      var engineConf = siteConfig.seo_engine || {};
      var configuredPath = String(engineConf.site_path || '').trim();
      if (configuredPath) {
        sitePath = '/' + configuredPath.replace(/^\/+|\/+$/g, '');
        if (sitePath === '/') sitePath = '';
        else sitePath = sitePath.replace(/\/+$/, '');
      } else {
        var configuredUrl = String(engineConf.site_url || '').trim();
        if (configuredUrl) {
          try {
            var urlPath = new URL(configuredUrl).pathname.replace(/^\/+|\/+$/g, '');
            if (urlPath) sitePath = '/' + urlPath;
          } catch (e) { /* ignorar URLs invalidas */ }
        }
      }

      if (!mapaData.seo || typeof mapaData.seo !== 'object') return;

      var entries = _parseEntries(mapaData.seo);
      if (entries.length === 0) return;

      _injectCSS();
      var navHTML = _buildNavHTML(entries, sitePath);
      _injectIntoDOM(navHTML);
      _initBehavior();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', _init);
  } else {
    _init();
  }
})();
