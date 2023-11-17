<?php

use GuzzleHttp\Client;
use OpenAI\Util\Messages;
use Psr\Http\Message\ResponseInterface;

require_once __DIR__ . '/vendor/autoload.php';


$client = new Client();

$resp = $client->get("https://openai.hlhk.net", [
    "stream" => true
]);

$body = $resp->getBody();

while (!$body->eof()) {
    echo $body->read(1024);

}


