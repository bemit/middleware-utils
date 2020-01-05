<?php

namespace Orbiter\MiddlewareUtils;

use DI\Annotation\Inject;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple class to create instances of PSR-7 classes, seeded by PHP-DI
 */
trait HasResponseFactory {
    /**
     * @Inject
     * @var StreamFactoryInterface
     */
    protected $stream;

    /**
     * @Inject
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    private function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface {
        return $this->responseFactory->createResponse($code, $reasonPhrase);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param $data
     *
     * @throws \JsonException
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function respondJson(ResponseInterface $response, $data): ResponseInterface {
        $data = json_encode($data, JSON_THROW_ON_ERROR);

        $msg = $this->stream->createStream($data);

        return $response->withBody($msg)->withHeader('Content-Type', 'application/json');
    }

    private function create400(): ResponseInterface {
        return $this->createResponse(400, 'Bad Request');
    }

    private function create401(): ResponseInterface {
        return $this->createResponse(401, 'Unauthorized');
    }

    private function create402(): ResponseInterface {
        return $this->createResponse(402, 'Payment Required');
    }

    private function create403(): ResponseInterface {
        return $this->createResponse(403, 'Forbidden');
    }

    private function create404(): ResponseInterface {
        return $this->createResponse(404, 'Not Found');
    }

    private function create405(): ResponseInterface {
        return $this->createResponse(405, 'Method Not Allowed');
    }

    private function create409(): ResponseInterface {
        return $this->createResponse(409, 'Conflict');
    }

    private function create410(): ResponseInterface {
        return $this->createResponse(410, 'Gone');
    }

    private function create413(): ResponseInterface {
        return $this->createResponse(413, 'Payload Too Large');
    }

    private function create415(): ResponseInterface {
        return $this->createResponse(415, 'Unsupported Media Type');
    }

    private function create440(): ResponseInterface {
        return $this->createResponse(440, 'Login Time-out');
    }

    private function create500(): ResponseInterface {
        return $this->createResponse(500, 'Internal Server Error');
    }

    private function create501(): ResponseInterface {
        return $this->createResponse(501, 'Not Implemented');
    }

    private function create502(): ResponseInterface {
        return $this->createResponse(502, 'Bad Gateway');
    }

    private function create503(): ResponseInterface {
        return $this->createResponse(503, 'Service Unavailable');
    }
}
