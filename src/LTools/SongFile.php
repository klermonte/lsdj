<?php

namespace LTools;


class SongFile
{
    public $number;
    public $name;
    public $version;
    public $blocksCount;
    public $rawData;

    private $project;

    public function __construct($blocks)
    {
        $this->blocksCount = count($blocks);
        $this->rawData = FilePack::decompress(FilePack::merge($blocks));
    }

    public function getProject()
    {
        if ($this->project === null) {
            $this->project = new Project($this->rawData);
        }

        return $this->project;
    }
}