<?php

return [
    // the name of the signed requests signature header
    'signature_header' => env('SIGNATURE_HEADER', 'X-BaseSignature'),

    'algorithms' => [
        'request' => 'sha256',
        'webhook' => 'sha512',
        'whatever-you-want' => 'gost-crypto'
    ],

    // These are the types of requests that will be supported
    'types' => [
        'request',
        'webhook',
        'whatever-you-want'
    ],

    /**
     * These are the secretsauces used by each type of signed request you wish to handle
     */
    'secrets' => [
        'request' => env('REQUEST_SECRET', 'customize-this-request-secret'),
        'webhook' => env('WEBHOOK_SECRET', 'customize-this-webhook-secret'),
        'whatever-you-want' => env(
            'WHATEVER_YOU_WANT_SECRET',
            'customize-each-of-these-secrets-or-delete-if-unwanted!'
        ),
    ]
];