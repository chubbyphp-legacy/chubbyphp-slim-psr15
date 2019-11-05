<?php

declare(strict_types=1);

namespace Chubbyphp\SlimPsr15;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

final class LazyMiddlewareAdapter
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $serviceId;

    public function __construct(ContainerInterface $container, string $serviceId)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        /** @var MiddlewareInterface $middleware */
        $middleware = $this->container->get($this->serviceId);

        return $middleware->process($request, new MiddlewareRequestHandlerAdapter($response, $next));
    }
}
