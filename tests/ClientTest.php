<?php

use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
        $dotenv->load();
    }

    public function testInstance()
    {

        $client = new OpenAI\Client($_ENV["OPENAI_API_KEY"]);
        $this->assertInstanceOf(OpenAI\Client::class, $client);
        $this->assertInstanceOf(OpenAI\Models::class, $client->models());
    }
}
