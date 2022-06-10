<?php
namespace ChatAgency\LaravelSignedRequests;

use ChatAgency\LaravelSignedRequests\Contracts\SignatureValidatorInterface;

class DefaultSignatureValidator implements SignatureValidatorInterface
{
    public function validate(string $payload, string $type, string $signature, string $secret)
    {
        $algo = config('signed-requests.algorithms.' . $type);
        return $this->typeIsValid($type) && hash_hmac($algo, trim($payload), $secret) === $signature;
    }

    protected function typeIsValid($type)
    {
        return in_array($type, config('signed-requests.types'));
    }
}