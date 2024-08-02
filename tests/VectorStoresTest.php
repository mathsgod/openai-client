<?php

use PHPUnit\Framework\TestCase;

final class VectorStoresTest extends TestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
        $dotenv->load();
    }

    public function testInstance()
    {

        $client = new OpenAI\Client($_ENV["OPENAI_API_KEY"], $_ENV["OPENAI_API_BASE_URL"]);
        $vs = $client->vectorStores()->list();

        $this->assertIsArray($vs);

        //assert fields
        $this->assertArrayHasKey("data", $vs);

        //data is an array
        $this->assertIsArray($vs["data"]);

        //object is list
        $this->assertArrayHasKey("object", $vs);
    }
}
