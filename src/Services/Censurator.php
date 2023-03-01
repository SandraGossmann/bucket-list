<?php

namespace App\Services;

use function PHPUnit\Framework\stringContains;

class Censurator
{
    public function purify(string $word, string $text){
        if(str_contains($text, $word)){
            $nbLetters = strlen($word);
            $newText = str_ireplace($word, str_repeat('*', $nbLetters), $text);
            return $newText;
        }
        return $text;

    }
}