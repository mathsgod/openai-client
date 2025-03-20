<?php

namespace OpenAI;

use Evenement\EventEmitter;

class ResponseEventEmitter extends EventEmitter
{
    public function onOutputTextDelta($callback)
    {
        $this->on("response.output_text.delta", $callback);
    }

    public function onCreated($callback)
    {
        $this->on("response.created", $callback);
    }

    public function onCompleted($callback)
    {
        $this->on("response.completed", $callback);
    }

    public function onInProgress($callback)
    {
        $this->on("response.in_progress", $callback);
    }

    public function onOutputItemDone($callback)
    {
        $this->on("response.output_item.done", $callback);
    }

    public function onError($callback)
    {
        $this->on("error", $callback);
    }


    public function write($data)
    {

        //extract the data from the stream
        //first line is the event: name
        //second line is the data

        $lines = explode("\n", $data);
        $lines = array_filter($lines);

        $event_name = trim(explode(":",  $lines[0])[1]);
        $event_data = trim(explode(":",  $lines[1], 2)[1]);

        $data = json_decode($event_data, true);


        $this->emit($event_name, [$data]);
    }
}
