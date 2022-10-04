<?php

namespace ChatAgency\LaravelSignedRequests;

use Exception;
use ChatAgency\LaravelSignedRequests\DefaultSignatureValidator;
use ChatAgency\LaravelSignedRequests\Contracts\SignatureValidatorInterface;

/**
 * Validate and process webhook
 */
class SignedRequestService
{
    protected string $type;

    protected string $payload;

    protected string $signature;

    protected string $secret;

    protected SignatureValidatorInterface $validator;

    /**
     * Constructor
     *
     * @param SignatureValidatorInterface $validator
     *
     * @return self
     */
    public function __construct(SignatureValidatorInterface $validator = null)
    {
        $this->validator = $validator ?? new DefaultSignatureValidator();

        return $this;
    }

    public static function make(...$args)
    {
        return new static(...$args);
    }

    /**
     * getSignatureHeaderName
     *
     * @return mixed
     */
    public function getSignatureHeaderName()
    {
        return config('signed-requests.signature_header');
    }

    /**
     * Get the type from this instance
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get payload
     *
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set the type
     *
     * @param  string $type
     * @return self
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set payload
     *
     * @param mixed $payload expects string or array
     *
     * @return self
     */
    public function setPayload($payload)
    {
        if (is_array($payload)) {
            $payload = json_encode($payload);
        }

        $this->payload = $payload;

        return $this;
    }

    /**
     * generate hash for request type and loaded payload
     *
     * @param  string $type  the type of request
     * @return string
     */
    public function hashPayload(string $type)
    {
        $p = $this->payload;
        $algo = config('signed-requests.algorithms.' . $type);
        $secret = config('signed-requests.secrets.'.$type);

        return hash_hmac($algo, trim($p), $secret);
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return  self
     */
    public function setSignature(string $signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set secret
     *
     * @param string $secret
     *
     * @return self
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Set signature validator
     *
     * @param SignatureValidatorInterface $validator
     *
     * @return self
     */
    public function setValidator(SignatureValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Checks if signarse is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->validator->validate(
            $this->payload,
            $this->type,
            $this->signature,
            $this->secret
        );
    }
}
