<?php

namespace OpenAI;

use IteratorAggregate;
use Traversable;

class Files //implements IteratorAggregate
{

    /*    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->list());
    }
 */
    private $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $body)
    {
        $data = [];
        foreach ($body as $name => $value) {
            // If the value is a file path, open it as a CURLFile
            if ($name === 'file' && is_string($value) && file_exists($value)) {
                $data[] = [
                    "name" => $name,
                    "contents" => fopen($value, 'r'),
                    "filename" => basename($value)
                ];
            } else {
                $data[] = [
                    "name" => $name,
                    "contents" => $value
                ];
            }
        }

        return $this->client->post("files", [
            "multipart" => $data
        ]);
    }

    public function list()
    {
        return $this->client->get("files")["data"];
    }


    public function retrieve(string $file_id)
    {
        return $this->client->get("files/$file_id");
    }

    public function delete(string $file_id)
    {
        return $this->client->delete("files/$file_id");
    }
}
