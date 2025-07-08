<?php

namespace OpenAI;

class File
{
    /**
     * @deprecated This class is deprecated and will be removed in a future release.
     */
    private $client;
    /**
     * @deprecated This class is deprecated and will be removed in a future release.
     */
    private $file_id;

    /**
     * @deprecated This class is deprecated and will be removed in a future release.
     */
    public function __construct(Client $client, string $file_id)
    {
        $this->client = $client;
        $this->file_id = $file_id;
    }

    /**
     * @deprecated This method is deprecated and will be removed in a future release.
     */
    public function create(array $body)
    {
        return $this->client->post('files', $body);
    }

    /**
     * @deprecated This method is deprecated and will be removed in a future release.
     */
    public function retrieve()
    {
        return $this->client->get("files/$this->file_id");
    }

    /**
     * @deprecated This method is deprecated and will be removed in a future release.
     */
    public function delete()
    {
        return $this->client->delete("files/$this->file_id");
    }

    /**
     * @deprecated This method is deprecated and will be removed in a future release.
     */
    public function content()
    {
        return $this->client->get("files/$this->file_id/content");
    }
}
