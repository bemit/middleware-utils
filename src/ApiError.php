<?php

namespace Orbiter\MiddlewareUtils;

class ApiError implements ApiErrorInterface {
    protected $message = '';
    protected $code = 0;

    public function __construct(string $message, int $code = 0) {
        $this->message = $message;
        $this->code = $code;
    }

    public function jsonSerialize() {
        return [
            'message' => $this->message,
            'code' => $this->code,
        ];
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getCode(): int {
        return $this->code;
    }
}
