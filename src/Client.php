<?php

namespace OpenAI;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $client;
    public $model;
    public $chatCompletion;
    public $completion;
    public $edit;
    public $embedding;
    public $audio;
    public $file;
    public $image;

    private $max_retries;


    public function __construct(string $openai_api_key, int $max_retries = 10)
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
        $this->file = new File($this->client);
        $this->image = new Image($this->client);

        $this->logger = new NullLogger();

        $this->max_retries = $max_retries;
    }

    public function createImage(array $body)
    {
        return $this->image->create($body);
    }

    public function listFiles()
    {
        return $this->file->list();
    }

    public function createTranslation($file, string $model = "whisper-1")
    {
        return $this->audio->translate([
            "file" => $file,
            "model" => $model
        ]);
    }

    public function createTranscription($file, string $model = "whisper-1")
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
            return 5000 * (1 + $numberOfRetries);
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
            // Limit the number of retries to max_retries
            if ($retries >= $this->max_retries) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    $this->logger->warning("OpenAI server error, retrying", [
                        "status" => $response->getStatusCode(),
                        "message" => $response->getBody()->getContents(),
                        "retries" => $retries
                    ]);
                    return true;
                }

                // Retry on rate limit exceeded
                if ($response->getStatusCode() == 429) {
                    $this->logger->warning("OpenAI server rate limit exceeded, retrying", [
                        "status" => $response->getStatusCode(),
                        "message" => $response->getBody()->getContents(),
                        "retries" => $retries,
                    ]);


                    return true;
                }
            }

            return false;
        };
    }
}
