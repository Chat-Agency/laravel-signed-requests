<?php

namespace ChatAgency\LaravelSignedRequests\Contracts;

interface SignatureValidatorInterface
{
    /**
     * Validates the request signature
     *
     * @param  string $payload
     * @param  string $type
     * @param  string $signature
     * @param  string $secret
     * @return void
     */
    public function validate(string $payload, string $type, string $signature, string $secret);
}