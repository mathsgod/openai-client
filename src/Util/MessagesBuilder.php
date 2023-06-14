<?php

namespace OpenAI\Util;

class MessagesBuilder
{
    private $system_messages = [];
    private $chat_messages = [];

    private $max_token = 4096;

    public function __construct(int $max_token = 4096)
    {
        $this->max_token = $max_token;
    }

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

    public function getChatMessages()
    {
        return $this->chat_messages;
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

    public function addFunctionMessage(string $content, string $name)
    {
        $this->chat_messages[] = [
            "role" => "function",
            "content" => $content
        ];
    }


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

        $answer_token = 1000;
        $token_left = $this->max_token -  $system_count - $answer_token;

        //the chat message token should be less than token_left

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
