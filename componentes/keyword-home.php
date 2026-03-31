
<?php


function highlightKeywordAndInterlinks($text, $keyword, $interlinks = []) {
    $normalizeChar = function ($char) {
        $accentedChars = [
            'A' => 'AÁÀÂÄ', 'E' => 'EÉÈÊË', 'I' => 'IÍÌÎÏ',
            'O' => 'OÓÒÔÖ', 'U' => 'UÚÙÛÜ', 'a' => 'aáàâä',
            'e' => 'eéèêë', 'i' => 'iíìîï', 'o' => 'oóòôö',
            'u' => 'uúùûü', 'N' => 'NÑ', 'n' => 'nñ'
        ];
        return isset($accentedChars[$char]) ? '[' . $accentedChars[$char] . ']' : $char;
    };

    $prepositions = ['a', 'ante', 'bajo', 'cabe', 'con', 'contra', 'de', 'desde', 'durante', 'en', 'entre', 'hacia', 'hasta', 'mediante', 'para', 'por', 'según', 'sin', 'so', 'sobre', 'tras', 'versus', 'vía'];

    if (!empty($interlinks)) {
        foreach ($interlinks as $interlinkText => $slug) {
            $interlinkPattern = '';
            $interlinkTextLength = mb_strlen($interlinkText);
            for ($i = 0; $i < $interlinkTextLength; $i++) {
                $interlinkPattern .= $normalizeChar(mb_substr($interlinkText, $i, 1));
            }
            $pattern = "/($interlinkPattern)/ui";
            $replacement = function ($matches) use ($slug) {
                return "<a href='{$slug}.php'>{$matches[0]}</a>";
            };
            $text = preg_replace_callback($pattern, $replacement, $text);
        }
    }

    $keywordPattern = '';
    $keywordLength = mb_strlen($keyword);
    for ($i = 0; $i < $keywordLength; $i++) {
        $keywordPattern .= $normalizeChar(mb_substr($keyword, $i, 1));
    }

    $pattern = "/\b($keywordPattern)\b/ui";
    $replacement = "<strong>$0</strong>";
    $text = preg_replace($pattern, $replacement, $text);

    return $text;
}

$keyword = $home['keyword'];

$interlinks = [];
if (!empty($home['interlink'])) {
    $interlinkString = $home['interlink'];
    $interlinkArray = explode(", ", $interlinkString);
    foreach ($interlinkArray as $interlinkText) {
        $slug = createSlug($interlinkText);
        $interlinks[$interlinkText] = $slug;
    }
}
?>