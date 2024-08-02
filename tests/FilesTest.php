<?php

use PHPUnit\Framework\TestCase;

final class FilesTest extends TestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
        $dotenv->load();
    }

    public function testInstance()
    {
        $client = new OpenAI\Client($_ENV["OPENAI_API_KEY"]);

        $files = $client->files()->list();

        $this->assertIsArray($files);
    }

    public function testUpload()
    {
        $client = new OpenAI\Client($_ENV["OPENAI_API_KEY"]);

        $file = $client->files()->create([
            "purpose" => "assistants",
            "file" => fopen(__DIR__ . "/test.txt", "r")
        ]);

        $this->assertIsArray($file);

        //field id 
        $this->assertArrayHasKey("id", $file);


        //test retrieve file
        $f = $client->files()->retrieve($file["id"]);
        $this->assertIsArray($f);
        //field id
        $this->assertEquals($f["id"], $file["id"]);


        //delete file
        $delete = $client->files()->delete($file["id"]);
        $this->assertIsArray($delete);
        //field deleted
        $this->assertTrue($delete["deleted"]);

        
    }
}
