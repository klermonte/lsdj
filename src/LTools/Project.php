<?php

namespace LTools;


use Zerg\DataSet;
use Zerg\Field\Collection;
use Zerg\Stream\StringStream;

class Project
{
    public $data;

    /**
     * @var Collection
     */
    private $songSchema;

    public function __construct($rawData)
    {
        $stream = new StringStream($rawData);
        $this->data = $this->getSchema()->parse($stream)->getData();
    }

    /**
     * @return \Zerg\Field\Collection
     */
    private function getSchema()
    {
        if ($this->songSchema === null) {
            $this->songSchema = new Collection(Schema::getSong());
        }

        return $this->songSchema->setDataSet(new DataSet());
    }
}