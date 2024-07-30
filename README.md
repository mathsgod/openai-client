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


### Chat completion

```php

$data = $client->chatCompletions()->create([
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "user", "content" => "Hi"]
    ]
]);

print_r($data);
```

```
Array
(
    [id] => chatcmpl-1234
    [object] => chat.completion
    [created] => 1722324090
    [model] => gpt-4o-mini-2024-07-18
    [choices] => Array
        (
            [0] => Array
                (
                    [index] => 0
                    [message] => Array
                        (
                            [role] => assistant
                            [content] => Hello! How can I assist you today?
                        )

                    [logprobs] =>
                    [finish_reason] => stop
                )

        )

    [usage] => Array
        (
            [prompt_tokens] => 8
            [completion_tokens] => 9
            [total_tokens] => 17
        )

    [system_fingerprint] => fp_1234
)
```

#### Function call

```php
$data=$client->chatCompletions()->create([
   "model" => "gpt-4o-mini",
    "messages" => [
      ["role" => "user", "content" => "What is the price of iphone14?"]
    ],
    "functions" =>[
        [
            "name" => "get_iphone_price",
            "description" => "Get the price of iphone",
            "parameters" => [
                "type" => "object",
                "properties" => [
                    "model" => [
                        "type" => "string",
                        "description" => "The model of the iphone"
                    ]
                ],
                "required" => ["model"]
            ],
        ]
    ]
]);

print_r($data);
```

```
Array
(
    [id] => chatcmpl-1234
    [object] => chat.completion
    [created] => 1722324296
    [model] => gpt-4o-mini-2024-07-18
    [choices] => Array
        (
            [0] => Array
                (
                    [index] => 0
                    [message] => Array
                        (
                            [role] => assistant
                            [content] =>
                            [function_call] => Array
                                (
                                    [name] => get_iphone_price
                                    [arguments] => {"model":"iPhone 14"}
                                )

                        )

                    [logprobs] =>
                    [finish_reason] => function_call
                )

        )

    [usage] => Array
        (
            [prompt_tokens] => 60
            [completion_tokens] => 19
            [total_tokens] => 79
        )

    [system_fingerprint] => fp_1234
)
```
### Images

#### Create image
   
```php 
print_r($client->images()->generations([
    "model" => "dall-e-3",
    "prompt" => "a white siamese cat",
    "n" => 1,
    "size" => "1024x1024"
]));
```

### Embeddings

```php
print_r($client->embeddings()->create([
    "model" => "text-embedding-3-small",
    "input"=>"I feel great",
]));
```



