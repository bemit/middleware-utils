<?php

namespace Orbiter\MiddlewareUtils;

interface ApiErrorInterface extends \JsonSerializable {
    public function __construct(string $message, int $code = 0);

    public function getMessage(): string;

    public function getCode(): int;
}
