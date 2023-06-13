# openai-client

A very simple client for the OpenAI API.

## Installation

```bash
composer require mathsgod/openai-client
```

## Usage
Create a client instance with your API key.
```php
use OpenAI\Client;
$client=new Client("OPEN_API_KEY");
```

### Completion

```php
$response=$client->createCompletion([
    "model"=>"text-davinci-003",
    "prompt"=>"Say this is a test",
    "max_tokens"=>7,
    "temperature"=>0,
])
/*
{
  "id": "cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7",
  "object": "text_completion",
  "created": 1589478378,
  "model": "text-davinci-003",
  "choices": [
    {
      "text": "\n\nThis is indeed a test",
      "index": 0,
      "logprobs": null,
      "finish_reason": "length"
    }
  ],
  "usage": {
    "prompt_tokens": 5,
    "completion_tokens": 7,
    "total_tokens": 12
  }
}
*/
```

### Chat completion

```php

$response=$client->createChatCompletion([
    "model"=>"gpt-3.5-turbo",
    "messages"=>[
        ["role"=>"user","content"=>"Hello world"],
    ],
]);

/*
{
  "id": "chatcmpl-123",
  "object": "chat.completion",
  "created": 1677652288,
  "choices": [{
    "index": 0,
    "message": {
      "role": "assistant",
      "content": "\n\nHello there, how may I assist you today?",
    },
    "finish_reason": "stop"
  }],
  "usage": {
    "prompt_tokens": 9,
    "completion_tokens": 12,
    "total_tokens": 21
  }
}
*/
```

### Images

#### Create image
   
```php 
$response=$client->createImage([
    "prompt" => "A cute baby sea otter",
    "n"=>2
]);
/*
{
  "created": 1589478378,
  "data": [
    {
      "url": "https://..."
    },
    {
      "url": "https://..."
    }
  ]
}
*/
```


### Embeddings

```php
$response = $client->createEmbedding([
    "model"=>"text-embedding-ada-002",
    "input"=>"The food was delicious and the waiter..."
]);

/*
{
  "object": "list",
  "data": [
    {
      "object": "embedding",
      "embedding": [
        0.0023064255,
        -0.009327292,
        .... (1536 floats total for ada-002)
        -0.0028842222,
      ],
      "index": 0
    }
  ],
  "model": "text-embedding-ada-002",
  "usage": {
    "prompt_tokens": 8,
    "total_tokens": 8
  }
}
*/
```

### Audio

#### Create transcription

```php
$response=$client->createTranscription(fopen(__DIR__ . "/test.mp3","r"));
```

#### Create translation
Translates audio into into English.
```php
$respnose=$client->createTranslation(fopen(__DIR__ . "/test.mp3","r"));
```


