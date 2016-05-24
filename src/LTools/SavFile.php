<?php

namespace LTools;

use Zerg\Field\Collection;
use Zerg\Stream\FileStream;

class SavFile
{
    private $songFiles = [];

    public function __construct($fileName)
    {
        $stream = new FileStream($fileName);

        $mainSchema = new Collection(Schema::getFileManagement());

        $packed = $mainSchema->parse($stream);

        $this->songFiles = $this->fillSongFiles($packed);
    }

    /**
     * @return SongFile[]
     */
    public function getSongFiles()
    {
        return $this->songFiles;
    }

    private function fillSongFiles($fileSystem)
    {
        $fileBlocks = [];
//        echo max(array_keys($fileSystem['blocks']));die;
//        print_r($fileSystem['header']['blockToFile']);die;
        foreach ($fileSystem['header']['blockToFile'] as $blockNumber => $fileNumber) {
            if ($fileNumber == 0xff) {
                continue;
            }

            $fileBlocks[$fileNumber][$blockNumber + 1] = $fileSystem['blocks'][$blockNumber];
        }

        $files = [];
        foreach ($fileBlocks as $fileNumber => $blocks) {
            $file = new SongFile($blocks);
            $file->name = $fileSystem['header']['fileNames'][$fileNumber];
            $file->version = $fileSystem['header']['fileVersions'][$fileNumber];
            $file->number = $fileNumber;
            $files[] = $file;
        }

        return $files;
    }

}