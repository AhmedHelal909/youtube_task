<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the bcrypt algorithm.
    | These options will be used to adjust the amount of time it takes to
    | generate a hash. Thus, the security of hashes can be increased!
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the argon algorithm.
    | These options will be used to adjust the amount of time it takes to
    | generate a hash. Thus, the security of hashes can be increased!
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],

];
