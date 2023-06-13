<?php

namespace OpenAI\Util;

use Exception;
use Gioni06\Gpt3Tokenizer\Gpt3TokenizerConfig;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;

class Token
{
    static function Count(string $content)
    {

        $tokenizer = new Gpt3Tokenizer(new Gpt3TokenizerConfig());

        return count($tokenizer->encode($content));
    }

    static function CountMessages(array $messages)
    {
        $token = 0;
        foreach ($messages as $m) {
            $token += 4;
            $token += self::Count($m["content"]);
        }
        return $token;
    }
}
