<?php

namespace OpenAI;

class Run
{
    private $client;
    private $thread_id;
    private $run_id;

    public function __construct(Client $client, string $thread_id, string $run_id)
    {
        $this->client = $client;
        $this->thread_id = $thread_id;
        $this->run_id = $run_id;
    }

    public function submitToolOutputs(array $body)
    {
        return $this->client->post("threads/$this->thread_id/runs/$this->run_id/submit_tool_outputs", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ],
            "json" => $body
        ]);
    }

    public function submitToolOutputsAsStream(array $body)
    {
        $browser = new \React\Http\Browser();
        $body["stream"] = true;

        $promise = $browser->requestStreaming("POST", $this->client->base_url . "threads/$this->thread_id/runs/$this->run_id/submit_tool_outputs", [
            "Authorization" => "Bearer " . $this->client->openai_api_key,
            "Content-Type" => "application/json",
            "OpenAI-Beta" => "assistants=v2"
        ], json_encode($body, JSON_UNESCAPED_UNICODE));

        return Runs::ProcessPromise($promise);
    }

    public function steps()
    {
        return $this->client->get("threads/$this->thread_id/runs/$this->run_id/steps", [
            "headers" => [
                "OpenAI-Beta" => "assistants=v2"
            ]
        ]);
    }
}
