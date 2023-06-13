<?php

namespace OpenAI;

use CurlHandle;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class Client
{
    private $client;


    public $model;
    public $chatCompletion;
    public $completion;
    public $edit;
    public $embedding;
    public $audio;


    public function __construct(string $openai_api_key)
    {
        $handerStack = HandlerStack::create(new CurlHandler());
        $handerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

        $this->client = new \GuzzleHttp\Client([
            "base_uri" => "https://api.openai.com/v1/",
            "verify" => false,
            "headers" => [
                "Authorization" => "Bearer " . $openai_api_key,
            ],
            "handler" => $handerStack
        ]);

        $this->model = new Model($this->client);
        $this->chatCompletion = new ChatCompletion($this->client);
        $this->completion = new Completion($this->client);
        $this->embedding = new Embedding($this->client);
        $this->edit = new Edit($this->client);
        $this->audio = new Audio($this->client);
    }

    public function createTranscription(string $file, string $model)
    {
        return $this->audio->transcribe([
            "file" => $file,
            "model" => $model
        ]);
    }

    public function createEdit(array $body)
    {
        return $this->edit->create($body);
    }

    public function createEmbedding(array $body)
    {
        return $this->embedding->create($body);
    }

    public function createCompletion(array $body)
    {
        return $this->completion->create($body);
    }

    public function retrieveModel(string $model)
    {
        return $this->model->retrieve($model);
    }

    public function listModels()
    {
        return $this->model->list();
    }

    public function createChatCompletion(array $body)
    {
        return $this->chatCompletion->create($body);
    }

    private function retryDelay()
    {
        return function ($numberOfRetries) {
            return 3000;
        };
    }

    private function retryDecider()
    {
        return function (
            $retries,
            \GuzzleHttp\Psr7\Request $request,
            \GuzzleHttp\Psr7\Response $response = null,
            \GuzzleHttp\Exception\RequestException $exception = null
        ) {
            /*             // Limit the number of retries to 5
            if ($retries >= 5) {
                return false;
            }
 */
            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    return true;
                }

                // Retry on rate limit exceeded
                if ($response->getStatusCode() == 429) {
                    return true;
                }
            }

            return false;
        };
    }



    private function sendRequest(string $uri, array $params)
    {
        while (true) {
            try {
                $response = $this->client->post($uri, [
                    "json" => $params
                ]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                if ($e->getCode() == 429) {
                    //                    echo "Too many requests, sleeping for 3 seconds\n";
                    sleep(3);
                } else {
                    return null;
                }
            }
        }
    }

    public function chat(array $options)
    {
        return $this->sendRequest("chat/completions", $options);
    }

    /**
     * 
     */
    public function transcriptions(array $options)
    {
        return $this->sendRequest("audio/transcription", $options);
    }


    public function speechToText(string $file)
    {

        $response = $this->openaiClient->audioTranscriptions()->create(
            new \Tectalic\OpenAi\Models\AudioTranscriptions\CreateRequest([
                'file' => $file,
                'model' => 'whisper-1',
            ])
        )->toModel();

        return $response->text;
    }


    public function getEmbedding(string $input)
    {

        while (true) {
            try {
                $response = $this->openaiClient->embeddings()->create(
                    new \Tectalic\OpenAi\Models\Embeddings\CreateRequest([
                        "model" => "text-embedding-ada-002",
                        "input" => $input
                    ])
                )->toModel();
                return $response->data[0]->embedding;
            } catch (Exception $e) {
                echo $e->getMessage();
                sleep(5);
            }
        };
    }


    public function ask(array $messages)
    {
        while (true) {
            try {
                $response = $this->openaiClient->chatCompletions()->create(
                    new \Tectalic\OpenAi\Models\ChatCompletions\CreateRequest([
                        'model' => 'gpt-3.5-turbo',
                        'messages' => $messages,
                        "temperature" => 0.5,
                    ])
                )->toModel();
                return $response->choices[0]->message->content;
            } catch (Exception $e) {
                $message = $e->getMessage();
                if ($message === "Unsuccessful response. HTTP status code: 429 (Too Many Requests).") {
                    sleep(2);
                } else {
                    return "";
                }
            }
        }
    }
}
