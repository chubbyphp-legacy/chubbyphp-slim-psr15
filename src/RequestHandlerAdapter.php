<?php

declare(strict_types=1);

namespace Chubbyphp\SlimPsr15;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandlerAdapter
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->requestHandler->handle($request);
    }
}
