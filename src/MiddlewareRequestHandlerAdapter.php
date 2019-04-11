<?php

declare(strict_types=1);

namespace Chubbyphp\SlimPsr15;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareRequestHandlerAdapter implements RequestHandlerInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var callable
     */
    private $next;

    /**
     * @param ResponseInterface $response
     * @param callable          $next
     */
    public function __construct(ResponseInterface $response, callable $next)
    {
        $this->response = $response;
        $this->next = $next;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $next = $this->next;

        return $next($request, $this->response);
    }
}
