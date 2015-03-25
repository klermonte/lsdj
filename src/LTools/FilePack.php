<?php

namespace LTools;


class FilePack
{
    static $defaultInstrByte;
    static $defaultInstr;
    static $defaultWaveByte;
    static $defaultWave;
    static $rleByte;
    static $specialByte;
    static $eofByte;

    public static function init()
    {
        self::$defaultInstrByte = chr(0xf1);
        self::$defaultWaveByte = chr(0xf0);
        self::$rleByte = chr(0xc0);
        self::$specialByte = chr(0xe0);
        self::$eofByte = chr(0xff);
        self::$defaultInstr =pack('H*','a80000ff0000030000d0000000f30000');
        self::$defaultWave = pack('H*','8ecdccbbaaa999888776665554433231');
    }

    public static function merge($blocks)
    {
        if (self::$defaultInstrByte === null) {
            self::init();
        }

        // merge file blocks

        $currentBlock = reset($blocks);
        $eof = false;
        $compressedData = '';

        while (!$eof) {

            $toAppend = 0;
            $nextBlock = '';

            $i = 0;

            while ($i < strlen($currentBlock) - 1) {

                $currentByte = $currentBlock[$i];
                $nextByte = $currentBlock[$i + 1];

                if ($currentByte == self::$rleByte) {
                    if ($nextByte == self::$rleByte) {
                        $i += 2;
                    } else {
                        $i += 3;
                    }
                } elseif ($currentByte == self::$specialByte) {
                    if ($nextByte == self::$defaultInstrByte || $nextByte == self::$defaultWaveByte) {
                        $i += 3;
                    } elseif ($nextByte == self::$specialByte) {
                        $i += 2;
                    } else {

                        $toAppend = $i;

                        if ($nextByte == self::$eofByte)
                            $eof = true;
                        else
                            $nextBlock = $blocks[ord($nextByte)];

                        break;
                    }
                } else {
                    $i += 1;
                }
            }

            $compressedData .= substr($currentBlock, 0, $toAppend);

            if (!$eof)
                $currentBlock = $nextBlock;

        }

        return $compressedData;
    }

    public static function decompress($compressedData)
    {
        // decompress data

        $rawData = '';

        $i = 0;
        while ($i < strlen($compressedData)) {

            $currentByte = $compressedData[$i];
            $i ++;

            if ($currentByte == self::$rleByte) {

                $currentByte = $compressedData[$i];
                $i ++;

                if ($currentByte == self::$rleByte) {

                    $rawData .= self::$rleByte;

                } else {

                    $count = ord($compressedData[$i]);
                    $i ++;

                    $rawData .= str_pad('', $count, $currentByte);
                }

            } elseif ($currentByte == self::$specialByte) {

                $currentByte = $compressedData[$i];
                $i ++;

                if ($currentByte == self::$specialByte) {

                    $rawData .= self::$specialByte;

                } elseif ($currentByte == self::$defaultWaveByte) {

                    $count = ord($compressedData[$i]);
                    $i ++;

                    $rawData .= str_pad('', $count * strlen(self::$defaultWave), self::$defaultWave);

                } elseif ($currentByte == self::$defaultInstrByte) {

                    $count = ord($compressedData[$i]);
                    $i ++;

                    $rawData .= str_pad('', $count * strlen(self::$defaultInstr), self::$defaultInstr);

                } elseif ($currentByte == self::$eofByte) {
                    throw new \Exception('Unexpected eof byte');
                } else {
                    throw new \Exception('Unexpected byte sequence');
                }

            } else {

                $rawData .= $currentByte;

            }

        }

        return $rawData;
    }


}