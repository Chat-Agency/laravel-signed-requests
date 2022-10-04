<?php

namespace ChatAgency\LaravelSignedRequests;

use ChatAgency\LaravelSignedRequests\Contracts\SignatureValidatorInterface;

class DefaultSignatureValidator implements SignatureValidatorInterface
{
    /**
     * Validate the signature
     *
     * @param  string $payload
     * @param  string $type
     * @param  string $signature
     * @param  string $secret
     * @return void
     */
    public function validate(string $payload, string $type, string $signature, string $secret)
    {
        $algo = config('signed-requests.algorithms.' . $type);
        return $this->typeIsValid($type) && hash_hmac($algo, trim($payload), $secret) === $signature;
    }

    /**
     * Validate the signature type
     *
     * @param  mixed $type
     * @return void
     */
    public function typeIsValid($type)
    {
        return in_array($type, config('signed-requests.types'));
    }
}
