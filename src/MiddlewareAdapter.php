<?php

declare(strict_types=1);

namespace Chubbyphp\SlimPsr15;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

final class MiddlewareAdapter
{
    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * @param MiddlewareInterface $middleware
     */
    public function __construct(MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $this->middleware->process($request, new MiddlewareRequestHandlerAdapter($response, $next));
    }
}
