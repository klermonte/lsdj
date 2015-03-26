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
                'direction'        => ['enum', 1, ['values' => ['down', 'up']]],
                'type'             => ['enum', 2, ['values' => ['hf', 'sawtooth', 'sine', 'square']]],
            ],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            ['padding', 3],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['values' => ['Invalid', 'L', 'R', 'LR']]],
            'phase_finetune'   => ['int', 'nibble'],
            'wave'             => ['enum', 2, ['values' => ['12.5%', '25%', '50%', '75%']]],

            /* 8 - 15 */
            ['padding', 8 * 8],
        ];
    }

    public static function getVaweInstrument()
    {
        return [
            /* 1 */
            ['padding', 5],
            'volume'           => ['enum', 2, ['values' => [0, 3, 2, 1]]],
            ['padding', 1],

            /* 2 */
            'repeat'           => ['int', 'nibble'],
            'synth'            => ['int', 'nibble'],

            /* 3 - 4 */
            ['padding', 16],

            /* 5 */
            'vibrato'          => [
                'direction'        => ['enum', 1, ['values' => ['down', 'up']]],
                'type'             => ['enum', 2, ['values' => ['hf', 'sawtooth', 'sine', 'square']]],
            ],
            'automate_2'       => ['int', 1],
            'automate'         => ['int', 1],
            ['padding', 3],

            /* 6 */
            'table'            => ['int', 5],
            'table_on'         => ['int', 1],
            ['padding', 2],

            /* 7 */
            'pan'              => ['enum', 2, ['values' => ['Invalid', 'L', 'R', 'LR']]],
            ['padding', 6],

            /* 8 */
            ['padding', 8],

            /* 9 */
            'play_type'        => ['enum', 2, ['values' => ['once', 'loop', 'ping-pong', 'manual']]],
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
                'direction'        => ['enum', 1, ['values' => ['down', 'up']]],
                'type'             => ['enum', 2, ['values' => ['hf', 'sawtooth', 'sine', 'square']]],
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
            'pan'              => ['enum', 2, ['values' => ['Invalid', 'L', 'R', 'LR']]],
            ['padding', 6],

            /* 8 */
            'pitch'            => ['int', 'byte'],

            /* 9 */
            'kit_2'            => ['int', 6],
            ['padding', 1],
            'keep_attack_2'    => ['int', 1],

            /* 10 */
            'dist_type'        => ['enum', 8, ['values' => [
                0xd0 => 'clip',
                0xd1 => 'shape',
                0xd2 => 'shap2',
                0xd3 => 'wrap'
            ]]],

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
            's_cmd'            => ['enum', 8, ['values' => ['free', 'stable']]],

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
            'pan'              => ['enum', 2, ['values' => ['Invalid', 'L', 'R', 'LR']]],
            ['padding', 6],

            /* 8 - 15 */
            ['padding', 8 * 8],
        ];
    }

    public static function getInstruments()
    {
        return [
            'type' => ['enum', 'byte', ['values' => ['pulse', 'wave', 'kit', 'noise'], 'default' => 'invalid']],
            'data' => ['conditional', './type', [
                'fields' => [
                    'pulse'   => self::getPulseInstrument(),
                    'wave'    => self::getVaweInstrument(),
                    'kit'     => self::getKitInstrument(),
                    'noise'   => self::getNoiseInstrument(),
                    'invalid' => [['padding', 15 * 8]]
                ]
            ]]
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
            'allophone' => ['enum', 'byte', ['values' => [
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
            ]]],
            'length' => ['int', 'byte']
        ];
    }

    public static function getTableCommand()
    {
        return [
            'fx' => ['enum', 'byte', ['values' => [
                0 => '-',
                1 => 'A',
                2 => 'C',
                3 => 'D',
                4 => 'E',
                5 => 'F',
                6 => 'G',
                7 => 'H',
                8 => 'K',
                9 => 'L',
                10 => 'M',
                11 => 'O',
                12 => 'P',
                13 => 'R',
                14 => 'S',
                15 => 'T',
                16 => 'V',
                17 => 'W',
                18 => 'Z'
            ], 'count' => [self::NUM_TABLES, self::STEPS_PER_TABLE]]],
            'val' => ['int', 'byte', ['count' => [self::NUM_TABLES, self::STEPS_PER_TABLE]]]
        ];
    }

    public static function getSong()
    {
        return [
            'phrase_notes'       => ['enum', 'byte', [
                'values' => self::getNotes(),
                'count'  => [self::NUM_PHRASES, self::STEPS_PER_PHRASE]
            ]],
            'bookmarks'          => ['int', 'byte', ['count' => self::NUM_BOOKMARKS]],
            ['padding', 96 * 8],
            'grooves'            => ['int', 'byte', ['count' => [self::NUM_GROOVES,  self::STEPS_PER_GROOVE]]],
            'song'               => ['collection', self::getChain(), ['count' => self::NUM_SONG_CHAINS]],
            'table_envelopes'    => ['int', 'byte', ['count' => [self::NUM_TABLES,  self::STEPS_PER_TABLE]]],
            'words'              => ['collection', self::getWord(), ['count' => [self::NUM_WORDS, self::WORD_LENGTH]]],
            'word_names'         => ['string', 4, ['count' => self::NUM_WORDS]],
            // Set to 'rb' on init
            'mem_init_flag_1'    => ['string', 2],
            'instrument_names'   => ['string', 5, ['count' => self::NUM_INSTRUMENTS]],
            ['padding', 102 * 8],
            'table_alloc_table'  => ['int', 'byte', ['count' => self::NUM_TABLES]],
            'instr_alloc_table'  => ['int', 'byte', ['count' => self::NUM_INSTRUMENTS]],
            'chain_phrases'      => ['int', 'byte', ['count' => [self::NUM_CHAINS, self::PHRASES_PER_CHAIN]]],
            'chain_transposes'   => ['int', 'byte', ['count' => [self::NUM_CHAINS, self::PHRASES_PER_CHAIN]]],
            'instruments'        => ['collection', self::getInstruments(), ['count' => self::NUM_INSTRUMENTS]],
            'table_transposes'   => ['int', 'byte', ['count' => [self::NUM_TABLES, self::STEPS_PER_TABLE]]],
            'table_cmd1'         => self::getTableCommand(),
            'table_cmd2'         => self::getTableCommand(),
            // Set to 'rb' on init
            'mem_init_flag_2'    => ['string', 2],
        ];
    }

    public static function getFileManagement()
    {
        return [
            ['padding', 0x8000 * 8],
            'header' => [
                'fileNames' => ['string', 8, ['count' => 32]],
                'fileVersions' => ['int', 'byte', ['count' => 32]],
                ['padding', 30 * 8],
                // Set to 'jk' on init
                'sRamInitCheck' => ['string', 2],
                'activeFile' => ['int', 'byte'],
                'blockToFile' => ['int', 'byte', ['count' => 191]]
            ],
            'blocks' => ['string', 512, ['count' => 191]]
        ];
    }
}