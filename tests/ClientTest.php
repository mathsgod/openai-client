<?php

use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testModels()
    {

        $client = new OpenAI\Client($_ENV["OPENAI_API_KEY"]);
        $this->assertInstanceOf(OpenAI\Client::class, $client);
        $this->assertInstanceOf(OpenAI\Models::class, $client->models());

        /*         $this->assertInstanceOf(OpenAI\VectorStores::class, $client->vectorStores());
        $this->assertInstanceOf(OpenAI\Assistant::class, $client->assistant("davinci"));
        $this->assertInstanceOf(OpenAI\Assistants::class, $client->assistants());
        $this->assertInstanceOf(OpenAI\Images::class, $client->images());
 */
    }
}
