<?php

namespace OpenAI\Util;

class Messages
{
    //remove message if token count is greater than max_token
    public static function Trim(array $messages, int $max_token = 4096)
    {
        //count the token of system
        $system_token = Token::CountMessages(array_filter($messages, function ($m) {
            return $m["role"] == "system";
        }));



        $token_left = $max_token - $system_token;

        $final = [];
        $messages = array_reverse($messages);

        foreach ($messages as $message) {
            if ($message["role"] == "system") {
                $final[] = $message;
                continue;
            }

            $token_count = Token::Count($message["content"]);

            if ($token_left >= $token_count) {
                $token_left -= $token_count;
                $final[] = $message;
            }
        }

        return array_reverse($final);
    }
}
