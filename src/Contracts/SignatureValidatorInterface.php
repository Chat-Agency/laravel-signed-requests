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
     */
    public function validate(string $payload, string $type, string $signature, string $secret);

    /**
     * Validate the request type
     *
     * @param string $type
     */
    public function typeIsValid(string $type);
}
