<?php

namespace App\Support;

class Result
{
    private bool $isSuccess;
    private mixed $value;
    private ?string $error;
    private ?string $message;

    public function __construct(bool $isSuccess, mixed $value = null, ?string $error = null, ?string $message = null)
    {
        $this->isSuccess = $isSuccess;
        $this->value = $value;
        $this->error = $error;
        $this->message = $message;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public static function success(?string $message = null, mixed $value = null): self
    {
        return new self(true, $value, null, $message);
    }

    public static function failure(string $error, ?string $message = null): self
    {
        return new self(false, null, $error, $message);
    }
}
