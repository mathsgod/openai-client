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


### Responses

```php
$tools = [
    [
        "type" => "function",
        "name" => "get_iphone_price",
        "description" => "Get the current price of an iPhone model.",
        "parameters" => [
            "type" => "object",
            "properties" => [
                "model" => [
                    "type" => "string",
                    "description" => "The iPhone model, e.g. iPhone 14 Pro Max"
                ]
            ],
            "required" => ["model"],
            "additional_properties" => false
        ]
    ],
    [
        "type" => "function",
        "name" => "get_iphone_release_date",
        "description" => "Get the release date of an iPhone model.",
        "parameters" => [
            "type" => "object",
            "properties" => [
                "model" => [
                    "type" => "string",
                    "description" => "The iPhone model, e.g. iPhone 14 Pro Max"
                ]
            ],
            "required" => ["model"],
            "additional_properties" => false
        ]
    ]
];

$data = $client->responses()->create([
    "model" => "gpt-4o-mini",
    "input" => "What is the price and release date of iPhone 14 Pro Max?",
    "tools" => $tools,
]);

$input = [];

foreach ($data["output"] as $output) {
    if ($output["name"] == "get_iphone_price") {
        $input[] = [
            "type" => "function_call_output",
            "call_id" => $output["call_id"],
            "output" => json_encode([
                "model" => "iPhone 14 Pro Max",
                "price" => "$1099"
            ]),
        ];
    }

    if ($output["name"] == "get_iphone_release_date") {
        $input[] = [
            "type" => "function_call_output",
            "call_id" => $output["call_id"],
            "output" => json_encode([
                "model" => "iPhone 14 Pro Max",
                "release_date" => "September 16, 2022"
            ]),
        ];
    }
}

$data = $client->responses()->create([
    "model" => "gpt-4o-mini",
    "input" => $input,
    "tools" => $tools,
    "previous_response_id" => $data["id"],
]);

print_r($data);

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


### Audio

#### Speech
```php
print_r($client->audio()->speech([
    "model"=>"tts-1",
    "input"=>"Hello, how are you?",
    "voice"=>"alloy"
]));
```

#### Transcriptions
```php
print_r($client->audio()->transcriptions([
    "model"=>"whisper-1",
    "file"=>fopen('/path/to/audio.mp3', 'r')
]));
```

#### Translation
```php
print_r($client->audio()->translation([
    "model"=>"whisper-1",
    "file"=>fopen('/path/to/audio.mp3', 'r')
]));
```

## Assistants

### Create

```php
$client->assistants()->create([
    "model" => "gpt-4o-mini",
]);
```    

### List
```php
$client->assistants()->list();
```

### Retrieve
```php
$client->assistants()->retrieve("asst_1234");
```

### Delete
```php
$client->assistant("asst_1234")->delete();
```

## Threads

### Create

```php
$client->threads()->create(); //return Thread object
```


## Messages

### Create

```php
$client->thread("thread_1234")->messages()->create([
    "role" => "user",
    "content" => "Hello"
]);
```

#### Create with stream

```php
$stream = $thread->runs()->createStream([
    "assistant_id" => "asst_1234",
]);

$stream->on("thread.message.delta", function ($data) {
    echo $data;
    echo "\n";
});

$stream->on("thread.message.completed", function ($data) {
    echo $data;
    echo "\n";
});

$stream->on("done", function () use (&$thread) {
    echo "End\n";
});

```



## Example

```php
$thread = $client->threads()->create([
    "messages" => [
        [
            "role" => "user",
            "content" => "Hi"
        ]
    ]
]);

$data=$thread->runs()->create([
    "assistant_id" => "asst_1234", //assistant_id
]);

print_r($data); 

```








