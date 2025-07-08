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

    private $max_retries;
    public $base_url;
    public $openai_api_key;

    public function __construct(string $openai_api_key, string $baseURL = "https://api.openai.com/v1/")
    {
        $handerStack = HandlerStack::create();
        $handerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

        $this->client = new \GuzzleHttp\Client([
            "base_uri" => $baseURL,
            "verify" => false,
            "headers" => [
                "Authorization" => "Bearer " . $openai_api_key,
            ],
            "handler" => $handerStack
        ]);
        $this->logger = new NullLogger();
        $this->max_retries = 10;
        $this->base_url = $baseURL;
        $this->openai_api_key = $openai_api_key;
    }

    

    public function response(string $response_id)
    {
        return new Response($this, $response_id);
    }

    public function vectorStores()
    {
        return new VectorStores($this);
    }

    public function assistant(string $assistant_id)
    {
        return new Assistant($this, $assistant_id);
    }

    public function assistants()
    {
        return new Assistants($this);
    }

    public function images()
    {
        return new Images($this);
    }

    public function moderations()
    {
        return new Moderations($this);
    }

    public function chatCompletions()
    {
        return new ChatCompletions($this);
    }

    public function responses()
    {
        return new Responses($this);
    }

    public function batches()
    {
        return new Batches($this);
    }

    public function audio()
    {
        return new Audio($this);
    }

    public function postRaw($uri, array $options = [])
    {
        $response = $this->client->post($uri, $options);
        return $response->getBody()->getContents();
    }

    public function getHttpClient()
    {
        return $this->client;
    }

    public function post($uri, array $options = []): array
    {
        $response = $this->client->post($uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete($uri, array $options = []): array
    {
        $response = $this->client->delete($uri, $options);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function get($uri, array $options = []): array
    {
        $response = $this->client->get($uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function models()
    {
        return new Models($this);
    }

    public function file(string $file_id)
    {
        return new File($this, $file_id);
    }

    public function files()
    {
        return new Files($this);
    }

    public function embeddings()
    {
        return new Embeddings($this);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function threads()
    {
        return new Threads($this);
    }

    public function thread(string $thread_id)
    {
        return new Thread($this, $thread_id);
    }

    public function getTest()
    {
        $response = $this->client->get("dashboard/billing/usage", [
            "query" => [
                "start_date" => "2024-02-22",
                "end_date" => "2024-02-23"
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUsage(string $date)
    {
        $response = $this->client->get("usage", [
            "query" => [
                "date" => $date
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
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
