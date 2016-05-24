<?php

namespace LTools;


class Schema
{
    /**
     * @var int Max. number of phrases
     */
    const NUM_PHRASES = 255;

    /**
     * @var int Max. number of tables
     */
    const NUM_TABLES = 32;

    /**
     * @var int Number of soft-synths
     */
    const NUM_SYNTHS = 16;

    /**
     * @var int Waves per soft-synth
     */
    const WAVES_PER_SYNTH = 16;

    /**
     * @var int Number of frames per wave
     */
    const FRAMES_PER_WAVE = 32;

    /**
     * @var int Number of entries in each table
     */
    const ENTRIES_PER_TABLE = 16;

    /**
     * @var int Max. number of instruments
     */
    const NUM_INSTRUMENTS = 64;

    /**
     * @var int Max. number of sequences in the whole song
     */
    const NUM_SONG_CHAINS = 256;

    /**
     * @var int Max. number of chains
     */
    const NUM_CHAINS = 128;

    /**
     * @var int Max. number of phrases per chain
     */
    const PHRASES_PER_CHAIN = 16;

    /**
     * @var int Max. number of grooves
     */
    const NUM_GROOVES = 32;

    /**
     * @var int Steps per phrase
     */
    const STEPS_PER_PHRASE = 16;

    /**
     * @var int Steps per groove
     */
    const STEPS_PER_GROOVE = 16;

    /**
     * @var int Steps per table
     */
    const STEPS_PER_TABLE = 16;

    /**
     * @var int Max. length of a "word"
     */
    const WORD_LENGTH = 0x10;

    /**
     * @var int Number of "words" in the speech instrument
     */
    const NUM_WORDS = 42;

    /**
     * @var int Number of bookmarks
     */
    const NUM_BOOKMARKS = 64;

    public static function getPulseInstrument()
    {
        return [
            /* 1 */
            'envelope'         => ['int', 'byte'],

            /* 2 */
            'phase_transpose'  => ['int', 'byte'],

            /* 3 */
            'sound_length'     => ['int', 6],
            'has_sound_length' => ['int', 1],
            ['padding', 1],

            /* 4 */
            'sweep'            => ['int', 'byte'],

            /* 5 */
            'vibrato'          => [
                'direction'        => ['enum', 1, ['down', 'up']],
                'type'             => ['enum', 2, ['hf', 'sawtooth', 'sine', 'square']],
            ],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            ['padding', 3],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['Invalid', 'L', 'R', 'LR']],
            'phase_finetune'   => ['int', 'nibble'],
            'wave'             => ['enum', 2, ['12.5%', '25%', '50%', '75%']],

            /* 8 - 15 */
            ['padding', 8 * 8],
        ];
    }

    public static function getVaweInstrument()
    {
        return [
            /* 1 */
            ['padding', 5],
            'volume'           => ['enum', 2, [0, 3, 2, 1]],
            ['padding', 1],

            /* 2 */
            'repeat'           => ['int', 'nibble'],
            'synth'            => ['int', 'nibble'],

            /* 3 - 4 */
            ['padding', 16],

            /* 5 */
            'vibrato'          => [
                'direction'        => ['enum', 1, ['down', 'up']],
                'type'             => ['enum', 2, ['hf', 'sawtooth', 'sine', 'square']],
            ],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            ['padding', 3],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['Invalid', 'L', 'R', 'LR']],
            ['padding', 6],

            /* 8 */
            ['padding', 8],

            /* 9 */
            'play_type'        => ['enum', 2, ['once', 'loop', 'ping-pong', 'manual']],
            ['padding', 6],

            /* 10 - 13 */
            ['padding', 8 * 4],

            /* 14 */
            'speed'            => ['int', 'nibble', ['valueCallback' => function($v) {return $v + 1;}]],
            'steps'            => ['int', 'nibble'],

            /* 15 */
            ['padding', 8],
        ];
    }

    public static function getKitInstrument()
    {
        return [
            /* 1 */
            'volume'           => ['int', 'byte'],

            /* 2 */
            'kit_1'            => ['int', 6, ['valueCallback' => function($v) {return $v + 1;}]],
            'half_speed'       => ['int', 1],
            'keep_attack_1'    => ['int', 1],

            /* 3 */
            'length_1'         => ['int', 'byte'],

            ['padding', 8],

            /* 5 */
            'vibrato'          => [
                'direction'        => ['enum', 1, ['down', 'up']],
                'type'             => ['enum', 2, ['hf', 'sawtooth', 'sine', 'square']],
            ],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            'loop_2'           => ['int', 1],
            'loop_1'           => ['int', 1],
            ['padding', 1],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['Invalid', 'L', 'R', 'LR']],
            ['padding', 6],

            /* 8 */
            'pitch'            => ['int', 'byte'],

            /* 9 */
            'kit_2'            => ['int', 6],
            ['padding', 1],
            'keep_attack_2'    => ['int', 1],

            /* 10 */
            'dist_type'        => ['enum', 8, [
                0xd0 => 'clip',
                0xd1 => 'shape',
                0xd2 => 'shap2',
                0xd3 => 'wrap'
            ]],

            /* 11 */
            'length_2'         => ['int', 'byte'],

            /* 12 */
            'offset_1'         => ['int', 'byte'],

            /* 13 */
            'offset_2'         => ['int', 'byte'],

            /* 14 - 15 */
            ['padding', 16],
        ];
    }

    public static function getNoiseInstrument()
    {
        return [
            /* 1 */
            'envelope'         => ['int', 'byte'],

            /* 2 */
            's_cmd'            => ['enum', 8, ['free', 'stable']],

            /* 3 */
            'sound_length'     => ['int', 6],
            'has_sound_length' => ['int', 1],
            ['padding', 1],

            /* 4 */
            'sweep'            => ['int', 'byte'],

            /* 5 */
            ['padding', 3],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            ['padding', 3],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['Invalid', 'L', 'R', 'LR']],
            ['padding', 6],

            /* 8 - 15 */
            ['padding', 8 * 8],
        ];
    }

    public static function getInstruments()
    {
        return [
            'type' => ['enum', 'byte', ['pulse', 'wave', 'kit', 'noise'], ['default' => 'invalid']],
            'data' => ['conditional', './type', [
                    'pulse'   => self::getPulseInstrument(),
                    'wave'    => self::getVaweInstrument(),
                    'kit'     => self::getKitInstrument(),
                    'noise'   => self::getNoiseInstrument(),
                    'invalid' => [['padding', 15 * 8]]
                ]
            ]
        ];
    }

    public static function getChain()
    {
        return [
            'pu1' => ['int', 'byte'],
            'pu2' => ['int', 'byte'],
            'wav' => ['int', 'byte'],
            'noi' => ['int', 'byte']
        ];
    }

    public static function getNotes()
    {
        $notes = ['---'];
        $octave = ['C ', 'C#', 'D ', 'D#', 'E ', 'F ', 'F#', 'G ', 'G#', 'A ', 'A#', 'B '];
        foreach (range(0x3, 0x10) as $i) {
            foreach ($octave as $note) {
                $notes[] = $note . $i;
            }
        }

        return $notes;
    }

    public static function getWord()
    {
        return [
            'allophone' => ['enum', 'byte', [
                0 => '-',
                1 => 'AA',
                2 => 'AE',
                3 => 'AO',
                4 => 'AR',
                5 => 'AW',
                6 => 'AX',
                7 => 'AY',
                8 => 'BB1',
                9 => 'BB2',
                10 => 'CH',
                11 => 'DD1',
                12 => 'DD2',
                13 => 'DH1',
                14 => 'DH2',
                15 => 'EH',
                16 => 'EL',
                17 => 'ER1',
                18 => 'ER2',
                19 => 'EY',
                20 => 'FF',
                21 => 'GG1',
                22 => 'GG2',
                23 => 'GG3',
                24 => 'HH1',
                25 => 'HH2',
                26 => 'IH',
                27 => 'IY',
                28 => 'JH',
                29 => 'KK1',
                30 => 'KK2',
                31 => 'KK3',
                32 => 'LL',
                33 => 'MM',
                34 => 'NG',
                35 => 'NN1',
                36 => 'NN2',
                37 => 'OR',
                38 => 'OW',
                39 => 'OY',
                40 => 'PP',
                41 => 'RR1',
                42 => 'RR2',
                43 => 'SH',
                44 => 'SS',
                45 => 'TH',
                46 => 'TT1',
                47 => 'TT2',
                48 => 'UH',
                49 => 'UW1',
                50 => 'UW2',
                51 => 'VV',
                52 => 'WH',
                53 => 'WW',
                54 => 'XR',
                55 => 'YR',
                56 => 'YY1',
                57 => 'YY2',
                58 => 'ZH',
                59 => 'ZZ'
            ]],
            'length' => ['int', 'byte']
        ];
    }

    public static function getTableCommand()
    {
        $values = [
            0  => '-', 1  => 'A', 2  => 'C', 3  => 'D', 4  => 'E', 5  => 'F', 6  => 'G', 7  => 'H', 8  => 'K', 9 => 'L',
            10 => 'M', 11 => 'O', 12 => 'P', 13 => 'R', 14 => 'S', 15 => 'T', 16 => 'V', 17 => 'W', 18 => 'Z'
        ];

        return [
            'fx'  => ['arr', self::NUM_TABLES, ['arr', self::STEPS_PER_TABLE, ['enum', 'byte', $values]]],
            'val' => ['arr', self::NUM_TABLES, ['arr', self::STEPS_PER_TABLE, ['int',  'byte']]],
        ];
    }

    public static function getSong()
    {
        return [
            'phrase_notes'       => ['arr', self::NUM_PHRASES, ['arr', self::STEPS_PER_PHRASE, ['enum', 'byte', self::getNotes()]]],
            'bookmarks'          => ['arr', self::NUM_BOOKMARKS, ['int', 'byte']],
                                    ['padding', 96 * 8],
            'grooves'            => ['arr', self::NUM_GROOVES, ['arr', self::STEPS_PER_GROOVE, ['int',  'byte']]],
            'song'               => ['arr', self::NUM_SONG_CHAINS, self::getChain()],
            'table_envelopes'    => ['arr', self::NUM_TABLES, ['arr', self::STEPS_PER_TABLE, ['int',  'byte']]],
            'words'              => ['arr', self::NUM_WORDS, ['arr', self::WORD_LENGTH, self::getWord()]],
            'word_names'         => ['arr', self::NUM_WORDS, ['string', 4 * 8]],
            'mem_init_flag_1'    => ['string', 2 * 8], // Set to 'rb' on init
            'instrument_names'   => ['arr', self::NUM_INSTRUMENTS, ['string', 5 * 8]],
                                    ['padding', 102 * 8],
            'table_alloc_table'  => ['arr', self::NUM_TABLES, ['int',  'byte']],
            'instr_alloc_table'  => ['arr', self::NUM_INSTRUMENTS, ['int',  'byte']],
            'chain_phrases'      => ['arr', self::NUM_CHAINS, ['arr', self::PHRASES_PER_CHAIN, ['int',  'byte']]],
            'chain_transposes'   => ['arr', self::NUM_CHAINS, ['arr', self::PHRASES_PER_CHAIN, ['int',  'byte']]],
            'instruments'        => ['arr', self::NUM_INSTRUMENTS, self::getInstruments()],
            'table_transposes'   => ['arr', self::NUM_TABLES, ['arr', self::STEPS_PER_TABLE, ['int',  'byte']]],
            'table_cmd1'         => self::getTableCommand(),
            'table_cmd2'         => self::getTableCommand(),
            'mem_init_flag_2'    => ['string', 2 * 8], // Set to 'rb' on init
        ];
    }

    public static function getFileManagement()
    {
        return [
                        ['padding', 0x8000 * 8],
            'header' => [
                'fileNames'     => ['arr', 32, ['string', 8 * 8]],
                'fileVersions'  => ['arr', 32, ['int', 'byte']],
                                   ['padding', 30 * 8],
                'sRamInitCheck' => ['string', 2 * 8], // Set to 'jk' on init
                'activeFile'    => ['int', 'byte'],
                'blockToFile'   => ['arr', 191, ['int', 'byte']]
            ],
            'blocks' => ['arr', 191, ['string', 512 * 8]]
        ];
    }
}