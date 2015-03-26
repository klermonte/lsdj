<?php

namespace LTools;


class SavFileTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $file = new SavFile(__DIR__ . '/../2nd.sav');
        $songFiles = $file->getSongFiles();
//        foreach ($songFiles as $songFile) {
//            echo $songFile->name . PHP_EOL;
//            $songFile->getProject();
            echo json_encode($songFiles[0]->getProject()->data['mem_init_flag_2'], JSON_PRETTY_PRINT)  . PHP_EOL;
//        }
    }
}