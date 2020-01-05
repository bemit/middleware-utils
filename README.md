# Middleware Utils

Utilities and middlewares for PSR-7/17, PSR-15 middleware systems for usage with PHP-DI.

## HasResponseFactory

Adds a response factory to a middleware with various factory methods, relies on DI-injection of `Psr\Http\Message\ResponseFactoryInterface` and `Psr\Http\Message\StreamFactoryInterface`.

```php
<?php
use Orbiter\MiddlewareUtils\HasResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SampleMiddleware implements MiddlewareInterface {
    use HasResponseFactory;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        if($request->hasHeader('error') === 'some-strange-bug') {
            // Simple Status Response
            return $this->create500();
            // Same like `create500`
            return $this->createResponse(500, 'Internal Server Error');
        }

        if($request->hasHeader('error') === 'client-error') {
            // Creating: 400 Bad Request with JSON Body
            return $this->respondJson($this->create400(), ['message' => 'client-error-msg']);
        }
        
        return $handler->handle($request);
    }
}
```

- `createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface` - create any empty response
- `respondJson(ResponseInterface $response, $data): ResponseInterface` - uses the response and adds header and data
- `create400(): ResponseInterface` - Bad Request
- `create401(): ResponseInterface` - Unauthorized
- `create402(): ResponseInterface` - Payment Required
- `create403(): ResponseInterface` - Forbidden
- `create404(): ResponseInterface` - Not Found
- `create405(): ResponseInterface` - Method Not Allowed
- `create409(): ResponseInterface` - Conflict
- `create410(): ResponseInterface` - Gone
- `create413(): ResponseInterface` - Payload Too Large
- `create415(): ResponseInterface` - Unsupported Media Type
- `create440(): ResponseInterface` - Login Time-out
- `create500(): ResponseInterface` - Internal Server Error
- `create501(): ResponseInterface` - Not Implemented
- `create502(): ResponseInterface` - Bad Gateway
- `create503(): ResponseInterface` - Service Unavailable

## ApiError

Unified error body:

```php
<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Orbiter\MiddlewareUtils\HasResponseFactory;
use Orbiter\MiddlewareUtils\ApiError;

class SampleMiddleware implements MiddlewareInterface {
    use HasResponseFactory;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        if($request->hasHeader('error') === 'user-not-found') {
            // Creating: 400 Bad Request with ApiError as JSON Body
            return $this->respondJson($this->create404(), new ApiError('User Not Found'));
        }
        
        return $handler->handle($request);
    }
}
```

## CorsMiddleware

Dead-Simple CORS middleware, support multiple origins.

Create the middleware with any DI-factory, add to any middleware pipe.

```php
<?php
use Orbiter\MiddlewareUtils\CorsMiddleware;

$pipe = new RespondPipe();

/**
 * @var DI\FactoryInterface $factory
 */
$pipe->with(
    $factory->make(CorsMiddleware::class, [
        'origins_allowed' => [
            'http://localhost:3000',
            'https://admin.example.org',
        ],
        'headers_allowed' => [
            'Content-Type',
            'Accept',
            'AUTHORIZATION',
            'X-Requested-With',
            'X_AUTH_TOKEN',
            'X_AUTH_SIGNATURE',
            'X_API_OPTION',
            'remember-me',
        ],
        'headers_expose' => [
            'Content-Range',
        ],
        'max_age' => 2,
    ])
);
```

### CORSMiddleware Zend-Expressive

```php
<?php
use Zend\Stratigility\MiddlewarePipe;

use DI\FactoryInterface;
use Orbiter\MiddlewareUtils\CorsMiddleware;

class PipelineFactory
{
    public function __invoke(FactoryInterface $factory)
    {
        $pipeline = new MiddlewarePipe();

        // create CORS Middleware with PHP-DI
        $pipeline->pipe($factory->make(CorsMiddleware::class, [
            'origins_allowed' => ['http://localhost:3000'],
            'headers_allowed' => [
                'Content-Type',
                'Accept',
                'AUTHORIZATION',
                'X-Requested-With',
                'X_AUTH_TOKEN',
                'X_AUTH_SIGNATURE',
                'X_API_OPTION',
                'remember-me',
            ],
            'headers_expose' => [
                'Content-Range',
            ],
            'max_age' => 2,
        ]));

        $pipeline->pipe(OtherMiddleware::class);
        // ...

        return $pipeline;
    }
}
```

## License

This project is free software distributed under the **MIT License**.

See: [LICENSE](LICENSE).

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***

Maintained by [Michael Becker](https://mlbr.xyz)
