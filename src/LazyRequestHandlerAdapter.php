<?php

declare(strict_types=1);

namespace Chubbyphp\SlimPsr15;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class LazyRequestHandlerAdapter
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @param ContainerInterface $container
     * @param string             $serviceId
     */
    public function __construct(ContainerInterface $container, string $serviceId)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var RequestHandlerInterface $requestHandler */
        $requestHandler = $this->container->get($this->serviceId);

        return $requestHandler->handle($request);
    }
}
