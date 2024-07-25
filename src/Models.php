<?php

namespace OpenAI;


class Models
{
    public $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list()
    {
        return $this->client->get("models")["data"];
    }

    public function retrieve(string $model)
    {
        return $this->client->get("models/$model");
    }
}
