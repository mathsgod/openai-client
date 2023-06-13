<?php

namespace OpenAI\Util;

class MessagesBuilder
{
    private $chat_messages = [];
    private $context_messages = [];
    private $system_messages = [];

    public function addSystemMessages(string $systemMessage)
    {
        $this->system_messages[] = $systemMessage;
    }

    /**
     * @return array<string>
     */
    public function getSystemMessages(): array
    {
        return $this->system_messages;
    }

    public function getContextMessages(): array
    {
        return $this->context_messages;
    }

    public function getChatMessages()
    {
        return $this->chat_messages;
    }


    public function addContextMessages(array $contextMessage)
    {
        $this->context_messages[] = $contextMessage;
    }

    public function addChatMessage($message)
    {
        $this->chat_messages[] = $message;
    }

    public function getTokenCount()
    {
        $token_count = 0;

        foreach ($this->chat_messages as $message) {
            $token_count += 4;
            $token_count += Token::Count($message["content"]);
        }

        return $token_count;
    }

    public function addUserMessage(string $content)
    {
        $this->chat_messages[] = [
            "role" => "user",
            "content" => $content
        ];
    }

    public function addAssistantMessage(string $content)
    {
        $this->chat_messages[] = [
            "role" => "assistant",
            "content" => $content
        ];
    }

    /*  public function getSummarizedChatMessages()
    {
        $messages = [];
        $s_messages = [];
        $token_count = 0;
        foreach (array_reverse($this->chat_messages) as $message) {
            $token_count += Token::Count($message["content"]);
            $token_count += 4;
            if ($token_count > 1000) {
                $s_messages[] = $message;
            } else {
                $messages[] = $message;
            }
        }

        $messages = array_reverse($messages);
        $s_messages = array_reverse($s_messages);

        if (!$s_messages) return $messages;

        $output = App::SummarizeMessage($s_messages);
        return array_merge([
            [
                "role" => "system",
                "content" => $output
            ]
        ], $messages);
    }
 */
    public function getMessages(): array
    {

        $input = [];

        if ($this->system_messages) {
            $input[] = [
                "role" => "system",
                "content" => implode("\n", $this->getSystemMessages())
            ];
        }

        //count this system message
        $system_count = Token::CountMessages($input);
        $context_count = Token::CountMessages($this->context_messages);

        $token_count = $system_count + $context_count;

        $answer_token = 1000;
        $token_left = 4096 - $token_count - $answer_token;

        //the chat message token should be less than token_left

        $input = array_merge($input, $this->context_messages);

        $limited_messages = $this->getLimitedChatMessage($token_left);

        $input = array_merge($input, $limited_messages);

        return $input;
    }

    public function getLimitedChatMessage(int $max_token)
    {

        $messages = [];
        $token_count = 0;
        foreach (array_reverse($this->chat_messages) as $message) {
            $messages[] = $message;
            $token_count += Token::Count($message["content"]);
            $token_count += 4;
            if ($token_count > $max_token) {
                break;
            }
        }

        return array_reverse($messages);
    }
}
