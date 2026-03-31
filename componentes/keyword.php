<?php 
function highlightKeywordAndInterlinks($text, $keyword, $interlinks = []) {
    // Normalizar caracteres con acento
    $normalizeChar = function ($char) {
        $accentedChars = [
            'A' => 'AÁÀÂÄ', 'E' => 'EÉÈÊË', 'I' => 'IÍÌÎÏ',
            'O' => 'OÓÒÔÖ', 'U' => 'UÚÙÛÜ', 'a' => 'aáàâä',
            'e' => 'eéèêë', 'i' => 'iíìîï', 'o' => 'oóòôö',
            'u' => 'uúùûü'
        ];
        return isset($accentedChars[$char]) ? '[' . $accentedChars[$char] . ']' : $char;
    };

    // Procesamiento de interlinks
    if (!empty($interlinks)) {
        foreach ($interlinks as $interlinkText => $slug) {
            $interlinkPattern = '';
            $interlinkTextLength = mb_strlen($interlinkText);
            for ($i = 0; $i < $interlinkTextLength; $i++) {
                $interlinkPattern .= $normalizeChar(mb_substr($interlinkText, $i, 1));
            }
            $pattern = "/($interlinkPattern)/ui";
            $replacement = function ($matches) use ($slug) {
                return "<strong><a href='{$slug}'>{$matches[0]}</a></strong>";
            };
            $text = preg_replace_callback($pattern, $replacement, $text);
        }
    }

    // Reemplazo de keyword, asegurándose de no reemplazar dentro de enlaces
    $keywordPattern = '';
    $keywordLength = mb_strlen($keyword);
    for ($i = 0; $i < $keywordLength; $i++) {
        $keywordPattern .= $normalizeChar(mb_substr($keyword, $i, 1));
    }
    $pattern = "/($keywordPattern)(?![^<]*>|[^<>]*<\/a)/ui";
    $replacement = "<strong>$1</strong>";
    $text = preg_replace($pattern, $replacement, $text);

    return $text;
}



// Uso de la función
$keyword = $data2['article']['keyword'];

// Verificar si el campo interlink existe y no está vacío
$interlinks = [];
if (!empty($data2['article']['interlink'])) {
    $interlinkString = $data2['article']['interlink'];
    $interlinkArray = explode(", ", $interlinkString);
    foreach ($interlinkArray as $interlinkText) {
        $slug = createSlug($interlinkText);
        $interlinks[$interlinkText] = $slug;
    }
}

// Ahora, la función manejará correctamente tanto la presencia como la ausencia de interlinks

?>