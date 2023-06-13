# openai-client

A simple client for the OpenAI API.

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
```

### Chat completion

```php

$response=$client->createChatCompletion([
    "model"=>"gpt-3.5-turbo",
    "messages"=>[
        ["role"=>"user","content"=>"Hello world"],
    ],
]);
```


### Images

#### Create image
   
```php 
$response=$client->createImage([
    "prompt" => "A cute baby sea otter"
]);
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
$response=$client->createTranscription(__DIR__ . "/test.mp3");
```

#### Create translation
Translates audio into into English.
```php
$respnose=$client->createTranslation(__DIR__ . "/test.mp3");
```


