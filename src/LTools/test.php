<?php

namespace LTools;

use Zerg\Field\Collection;
use Zerg\Stream\FileStream;
use Zerg\Stream\StringStream;

require_once('../../vendor/autoload.php');

$stream = new FileStream('../../1st.sav');

$mainSchema = new Collection([
    ['padding', 0x8000 * 8],
    'header' => [
        'fileNames' => ['string', 8, ['count' => 0x20]],
        'fileVersions' => ['int', 'byte', ['count' => 0x20]],
        ['padding', 30 * 8],
        'sRamInitCheck' => ['string', 2],
        'activeFile' => ['int', 'byte'],
        'blockToFile' => ['int', 'byte', ['count' => 191]]
    ],
    'blocks' => ['string', 0x200, ['count' => 191]]
]);

$pulseInstrument = [
    'envelope' => ['int', 'byte'],
    'phase_transpose' => ['int', 'byte'],

    'sound_length' => ['int', 6],
    'has_sound_length' => ['int', 1],
    ['padding', 1],

    'sweep' => ['int', 'byte'],

    'vibrato' => [
        'direction' => [
            'enum',
            1, [
                'values' => [
                    'down',
                    'up'
                ]
            ]
        ],
        'type' => ['int', 2],
    ],
    'automate_2' => ['int', 1],
    'automate_1' => ['int', 1],
    ['padding', 3],

    'table' => ['int', 5],
    'table_on' => ['int', 1],
    ['padding', 2],

    'pan' => ['enum', 2, [
        'values' => [
            'Invalid',
            'L',
            'R',
            'LR'
        ]
    ]],
    'phase_finetune' => ['int', 4],
    'wave' => ['int', 2],

    ['padding', 16],
    '_default_instr_byte_1' => ['int', 'byte'],
    ['padding', 24],
    '_default_instr_byte_2' => ['int', 'byte'],
    ['padding', 8],
];


$waveInstrument = [
    'volume' => ['int', 'byte'],

    'repeat' => ['int', 'nibble'],
    'synth' => ['int', 'nibble'],

    ['padding', 16],

    'vibrato' => [
        'direction' => ['enum', 1, [
            'values' => [
                'down',
                'up'
            ]
        ]],
        'type' => ['int', 2],
    ],
    'automate_2' => ['int', 1],
    'automate_1' => ['int', 1],
    ['padding', 3],

    'table' => ['int', 5],
    'table_on' => ['int', 1],
    ['padding', 2],

    'pan' => ['enum', 2, [
        'values' => [
            'Invalid',
            'L',
            'R',
            'LR'
        ]
    ]],
    ['padding', 6],

    ['padding', 8],

    'play_type' => ['enum', 2, [
        'values' => [
            'once',
            'loop',
            'ping-pong',
            'manual'
        ]
    ]],
    ['padding', 6],

    ['padding', 8 * 4],

    'speed' => ['int', 'nibble'],
    'steps' => ['int', 'nibble'],

    ['padding', 8],
];

$kitInstrument = [
    'volume' => ['int', 'byte'],

    'kit_1' => ['int', 6],
    'half_speed' => ['int', 1],
    'keep_attack_1' => ['int', 1],

    'length_1' => ['int', 'byte'],

    ['padding', 8],

    'vibrato' => [
        'direction' => ['enum', 1, [
            'values' => [
                'down',
                'up'
            ]
        ]],
        'type' => ['int', 2],
    ],
    'automate_2' => ['int', 1],
    'automate_1' => ['int', 1],
    'loop_2' => ['int', 1],
    'loop_1' => ['int', 1],
    ['padding', 1],

    'table' => ['int', 5],
    'table_on' => ['int', 1],
    ['padding', 2],

    'pan' => ['enum', 2, [
        'values' => [
            'Invalid',
            'L',
            'R',
            'LR'
        ]
    ]],
    ['padding', 6],

    'pitch' => ['int', 'byte'],

    'kit_2' => ['int', 6],
    ['padding', 1],
    'keep_attack_2' => ['int', 1],

    'dist_type' => ['enum', 8, [
        'values' => [
            0xd0 => 'clip',
            0xd1 => 'shape',
            0xd2 => 'shap2',
            0xd3 => 'wrap'
        ]
    ]],
    'length_2' => ['int', 'byte'],
    'offset_1' => ['int', 'byte'],
    'offset_2' => ['int', 'byte'],
    ['padding', 16],
];

$noiseInstrument = [

    'envelope' => ['int', 'byte'],

    's_cmd' => ['enum', 8, [
        'values' => [
            0 => "free",
            1 => "stable"
        ]
    ]],

    'sound_length' => ['int', 6],
    'has_sound_length' => ['int', 1],
    ['padding', 1],

    'sweep' => ['int', 'byte'],

    ['padding', 3],
    'automate_2' => ['int', 1],
    'automate_1' => ['int', 1],
    ['padding', 3],

    'table' => ['int', 5],
    'table_on' => ['int', 1],
    ['padding', 2],

    'pan' => ['enum', 2, [
        'values' => [
            'Invalid',
            'L',
            'R',
            'LR'
        ]
    ]],
    ['padding', 6],

    ['padding', 8 * 8],
];

$instrument = [
    'type' => ['enum', 'byte', [
        'values' => [
            'pulse',
            'wave',
            'kit',
            'noise'
        ], 'default' => 'invalid'
    ]],
    'data' => ['conditional', './type', [
        'fields' => [
            'pulse' => $pulseInstrument,
            'wave' => $waveInstrument,
            'kit' => $kitInstrument,
            'noise' => $noiseInstrument,
            'invalid' => ['padding', 15 * 8]
        ]
    ]]
];


/*
    ("chain_transposes", b.array(
        NUM_CHAINS, b.array(PHRASES_PER_CHAIN, b.byte))),
    ("instruments", b.array(NUM_INSTRUMENTS, instrument)),
    ("table_transposes", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx_val", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx2", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx2_val", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    # Set to 'rb' on init
    ("mem_init_flag_2", b.string(2)),
    ("phrase_alloc_table", b.array(NUM_PHRASES, b.boolean)),
    # There are only 255 valid phrases, but the allocation table is 256 bits
    # long, so we ignore the last bit
    b.padding(1),
    ("chain_alloc_table", b.array(NUM_CHAINS, b.boolean)),
    ("softsynth_params", b.array(NUM_SYNTHS, softsynth)),
    ("clock", [
        ("hours", b.byte),
        ("minutes", b.byte)]),
    ("tempo", b.byte),
    ("tune_setting", b.byte),
    ("total_clock", [
        ("days", b.byte),
        ("hours", b.byte),
        ("minutes", b.byte),
        ("checksum", b.byte)
    ]),
    ("key_delay", b.byte),
    ("key_repeat", b.byte),
    ("font", b.byte),
    ("sync_setting", b.byte),
    ("colorset", b.byte),
    b.padding(8),
    ("clone", b.enum(8, {
        0: "deep",
        1: "slim"
    })),
    ("file_changed", b.byte),
    ("power_save", b.byte),
    ("prelisten", b.byte),
    ("wave_synth_overwrite_lock", b.array(2, b.byte)),
    b.padding(8 * 58),
    # Beginning of bank 2
    ("phrase_fx", b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    ("phrase_fx_val", b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    b.padding(32 * 8),
    # Beginning of bank 3
    ("wave_frames",
     b.array(NUM_SYNTHS,
             b.array(WAVES_PER_SYNTH,
                     b.array(FRAMES_PER_WAVE, b.byte)))),
    ("phrase_instruments",
     b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    # Set to 'rb' on init
    ("mem_init_flag_3", b.string(2)),
    b.padding(13 * 8),
    ("version", b.byte)
]
*/
$result = $mainSchema->parse($stream);

$fileBlocks = array();
foreach ($result['header']['blockToFile'] as $blockNumber => $fileNumber) {

    if ($fileNumber == 0xff) continue;

    $fileBlocks[$fileNumber][$blockNumber + 1] = $result['blocks'][$blockNumber];

}

$defaultInstrByte = chr(0xf1);
$defaultInstr =pack('H*','a80000ff0000030000d0000000f30000');

$defaultWaveByte = chr(0xf0);
$defaultWave = pack('H*','8ecdccbbaaa999888776665554433231');

$rleByte = chr(0xc0);
$specialByte = chr(0xe0);
$eofByte = chr(0xff);

$files = array();

foreach ($fileBlocks as $fileNumber => $blocks) {

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

            if ($currentByte == $rleByte) {
                if ($nextByte == $rleByte) {
                    $i += 2;
                } else {
                    $i += 3;
                }
            } elseif ($currentByte == $specialByte) {
                if ($nextByte == $defaultInstrByte || $nextByte == $defaultWaveByte) {
                    $i += 3;
                } elseif ($nextByte == $specialByte) {
                    $i += 2;
                } else {

                    $toAppend = $i;

                    if ($nextByte == $eofByte)
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

    // decompress data

    $rawData = '';

    $i = 0;
    while ($i < strlen($compressedData)) {

        $currentByte = $compressedData[$i];
        $i ++;

        if ($currentByte == $rleByte) {

            $currentByte = $compressedData[$i];
            $i ++;

            if ($currentByte == $rleByte) {

                $rawData .= $rleByte;

            } else {

                $count = ord($compressedData[$i]);
                $i ++;

                $rawData .= str_pad('', $count, $currentByte);
            }

        } elseif ($currentByte == $specialByte) {

            $currentByte = $compressedData[$i];
            $i ++;

            if ($currentByte == $specialByte) {

                $rawData .= $specialByte;

            } elseif ($currentByte == $defaultWaveByte) {

                $count = ord($compressedData[$i]);
                $i ++;

                $rawData .= str_pad('', $count, $defaultWave);

            } elseif ($currentByte == $defaultInstrByte) {

                $count = ord($compressedData[$i]);
                $i ++;

                $rawData .= str_pad('', $count, $defaultInstr);

            } elseif ($currentByte == $eofByte) {
                throw new \Exception('Unexpected eof byte');
            } else {
                throw new \Exception('Unexpected byte sequence');
            }

        } else {

            $rawData .= $currentByte;

        }

    }

    $files[$fileNumber] = $rawData;

}


$songSchema = new Collection([
    'phrase_notes'      => ['int',       'byte',       ['count' => [255, 16]]],
    'bookmarks'         => ['int',       'byte',       ['count' => 64]       ],
    ['padding',   96 * 8                              ],
    'grooves'           => ['int',       'byte',       ['count' => [32,  16]]],
    'song'              => ['collection', [
        'pu1' => ['int', 'byte'],
        'pu2' => ['int', 'byte'],
        'wav' => ['int', 'byte'],
        'noi' => ['int', 'byte']
    ],                                                 ['count' => 256]      ],
    'table_envelopes'   => ['int',       'byte',       ['count' => [32,  16]]],
    'words'             => ['int',       'byte',       ['count' => [42,  32]]],
    'word_names'        => ['string',     4,           ['count' => 42]       ],
    'mem_init_flag_1'   => ['string',     2                                  ],
    'instrument_names'  => ['string',     5,           ['count' => 64]       ],
    ['padding',    102 * 8                            ],
    'table_alloc_table' => ['int',        'byte',      ['count' => 32]       ],
    'instr_alloc_table' => ['int',        'byte',      ['count' => 64]       ],
    'chain_phrases'     => ['int',        'byte',      ['count' => [128, 16]]],
    'chain_transposes'  => ['int',        'byte',      ['count' => [128, 16]]],
    'instruments'       => ['collection', $instrument, ['count' => 64]       ]
]);
$songStream = new StringStream($files[6]);
$springs = $songSchema->parse($songStream);

echo strlen(json_encode($springs->getData()));

/*

$songSchema = array(
    "phrase_notes" => Zerg::arr(255, Zerg::arr(16, Zerg::int(8))),
    "bookmarks" => Zerg::arr(64, Zerg::int(8)),
    Zerg::padding(96 * 8),
    "grooves" => Zerg::arr(32, Zerg::arr(16, Zerg::int(8))),
    "song" => Zerg::arr(256, $chain),
    "table_envelopes" => Zerg::arr(32, Zerg::arr(16, Zerg::int(8))),
    "words" => Zerg::arr(42, Zerg::arr(0x20, Zerg::int(8))),
    "word_names" => Zerg::arr(42, Zerg::string(4)),
    "mem_init_flag_1" => Zerg::string(2),
    "instrument_names" => Zerg::arr(64, Zerg::string(5)),
    Zerg::padding(70 * 8),
    Zerg::padding(32 * 8),
    "table_alloc_table" => Zerg::arr(32, Zerg::int(8)),
    "instr_alloc_table" => Zerg::arr(64, Zerg::int(8)),
    "chain_phrases" => Zerg::arr(128, Zerg::arr(16, Zerg::int(8))),
    "chain_transposes" => Zerg::arr(128, Zerg::arr(16, Zerg::int(8))),
    "instruments" => Zerg::arr(64, $instrument)
);*/

/*
    ("chain_transposes", b.array(
        NUM_CHAINS, b.array(PHRASES_PER_CHAIN, b.byte))),
    ("instruments", b.array(NUM_INSTRUMENTS, instrument)),
    ("table_transposes", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx_val", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx2", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    ("table_fx2_val", b.array(NUM_TABLES, b.array(STEPS_PER_TABLE, b.byte))),
    # Set to 'rb' on init
    ("mem_init_flag_2", b.string(2)),
    ("phrase_alloc_table", b.array(NUM_PHRASES, b.boolean)),
    # There are only 255 valid phrases, but the allocation table is 256 bits
    # long, so we ignore the last bit
    b.padding(1),
    ("chain_alloc_table", b.array(NUM_CHAINS, b.boolean)),
    ("softsynth_params", b.array(NUM_SYNTHS, softsynth)),
    ("clock", [
        ("hours", b.byte),
        ("minutes", b.byte)]),
    ("tempo", b.byte),
    ("tune_setting", b.byte),
    ("total_clock", [
        ("days", b.byte),
        ("hours", b.byte),
        ("minutes", b.byte),
        ("checksum", b.byte)
    ]),
    ("key_delay", b.byte),
    ("key_repeat", b.byte),
    ("font", b.byte),
    ("sync_setting", b.byte),
    ("colorset", b.byte),
    b.padding(8),
    ("clone", b.enum(8, {
        0: "deep",
        1: "slim"
    })),
    ("file_changed", b.byte),
    ("power_save", b.byte),
    ("prelisten", b.byte),
    ("wave_synth_overwrite_lock", b.array(2, b.byte)),
    b.padding(8 * 58),
    # Beginning of bank 2
    ("phrase_fx", b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    ("phrase_fx_val", b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    b.padding(32 * 8),
    # Beginning of bank 3
    ("wave_frames",
     b.array(NUM_SYNTHS,
             b.array(WAVES_PER_SYNTH,
                     b.array(FRAMES_PER_WAVE, b.byte)))),
    ("phrase_instruments",
     b.array(NUM_PHRASES, b.array(STEPS_PER_PHRASE, b.byte))),
    # Set to 'rb' on init
    ("mem_init_flag_3", b.string(2)),
    b.padding(13 * 8),
    ("version", b.byte)
]
*/