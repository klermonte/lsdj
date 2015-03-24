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
                ]
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

        if ($this->songSchema === null) {
            $this->songSchema = new Collection([
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
        }

        return $this->songSchema->setDataSet(new DataSet());
    }
}