<?php
function createSlug($string) {
    $string = strtolower($string); // Convertir a minúsculas
    // Preparar un mapa de caracteres para la traducción de caracteres especiales
    $map = array(
        '/á|à|â|ä|ã|å|ā/' => 'a',
        '/é|è|ê|ë|ē|ė|ę/' => 'e',
        '/í|ì|î|ï|ī|į|ı/' => 'i',
        '/ó|ò|ô|ö|õ|ø|ō|ơ/' => 'o',
        '/ú|ù|û|ü|ū|ų|ư/' => 'u',
        '/ç|ć|č|ĉ/' => 'c',
        '/ğ|ĝ|ğ|ġ|g/' => 'g',
        '/ł|ľ|ĺ|ļ|ŀ/' => 'l',
        '/ñ|ń|ň|ņ/' => 'n',
        '/ř|ŕ|ŗ/' => 'r',
        '/ś|š|ş|ș|ŝ|ss/' => 's',
        '/ť|ţ|ț|ŧ|t/' => 't',
        '/ý|ÿ|ŷ/' => 'y',
        '/ź|ž|ż/' => 'z',
        '/þ/' => 'th',
        '/Ð/' => 'dh',
        '/ß/' => 'ss',
        '/æ/' => 'ae',
        '/œ/' => 'oe',
        '/ƒ/' => 'f',
    );

    // Traducir los caracteres especiales
    $string = preg_replace(array_keys($map), array_values($map), $string);

    // Reemplazar espacios por guiones y remover caracteres no deseados
    $string = preg_replace('/\s+/', '-', $string); // Reemplazar espacios por guiones
    $string = preg_replace('/[^\w\-]+/', '', $string); // Remover caracteres que no sean alfanuméricos, guiones o guiones bajos
    $string = preg_replace('/\-+/', '-', $string); // Remover múltiples guiones si los hubiera
    return $string;
};
function getSocialMediaIcon($socialMediaName) {
    switch ($socialMediaName) {
        case 'facebook':
            return 'fa-facebook-f';
        case 'twitter':
            return 'fa-twitter';
        case 'instagram':
            return 'fa-instagram';
        case 'linkedin':
            return 'fa-linkedin-in';
        case 'tiktok':
            return 'fa-tiktok';
         case 'youtube':
            return 'fa-youtube';
        case 'doctoralia':
            return 'fa-medic';
        default:
            return ''; // Devuelve una cadena vacía o un ícono predeterminado si la red no está soportada
    }
};
function getImagePath($basePath, $slug, $defaultExt = 'jpg') {
    $extensions = ['png', 'webp', 'jpg'];
    foreach ($extensions as $ext) {
        if (file_exists($basePath . $slug . '.' . $ext)) {
            return $slug . '.' . $ext;
        }
    }
    return $slug . '.' . $defaultExt; // Devuelve la extensión predeterminada si no se encuentra ninguna otra
}
?>

