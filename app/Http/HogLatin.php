<?php

namespace App\Http;

/**
 * Class HogLatin.
 */
class HogLatin
{
    /**
     * @var array
     */
    private $vowels;

    /**
     * @var string
     */
    private $dialect;

    public function __construct(array $vowels, string $dialect)
    {
        $this->dialect = $dialect;
        $this->vowels = $vowels;
    }

    /**
     * Translate Latin to Pig Latin.
     *
     * @param string $text Incoming text.
     *
     * @return string
     */
    public function translate(string $text): string
    {
        if (is_string($text) && !strlen($text)) {
            return '';
        }

        if (!preg_match("/^[A-Za-z\.\,\!\-\*\`\s]+$/", $text)) {
            return '';
        }

        $data = explode(' ', $text);

        array_walk($data, function (&$item) {
            $item = $this->processWord($item);
        });

        return implode(' ', $data);
    }

    /**
     * Check if word has start vowel.
     *
     * @param string $word
     *
     * @return bool
     */
    private function isVowel(string $word): bool
    {
        $upper = array_map('strtoupper', $this->vowels);
        $vowels = array_merge($this->vowels, $upper);

        if (!in_array($word[0], $vowels)) {
            return false;
        }

        return true;
    }

    /**
     * Process word.
     *
     * @param string $word
     *
     * @return string
     */
    private function processWord(string $word): string
    {
        $symbol = '';
        if (strpbrk($word, '.,!-')) {
            $symbol = substr($word, strlen($word) - 1);
            $word = substr($word, 0, strlen($word) - 1);
        }
        $cpy_word = $word;

        if (!$this->isVowel($word)) {
            $word = $this->processConsonants($word);
        } else {
            $word .= $this->dialect;
        }

        if ($this->startsWithUpper($cpy_word)) {
            $word = ucfirst($word);
        }

        return $word.$symbol;
    }

    /**
     * Process consonants.
     *
     * @param string $word
     *
     * @return string
     */
    protected function processConsonants(string $word): string
    {
        $_word = '';
        for ($i = 0; $i < strlen($word); $i++) {
            if (!$this->isVowel($word[$i])) {
                $_word .= $word[$i];
            } else {
                break;
            }
        }

        $move = strspn($word, $_word);

        return strtolower(substr($word, $move).$_word.$this->dialect);
    }

    /**
     * Check if word starts with lower case.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function startsWithUpper(string $word): bool
    {
        $check = mb_strtolower($word);
        if ($word === $check) {
            return false;
        }

        return true;
    }
}
