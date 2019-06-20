<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\SlimPsr15;

use Chubbyphp\Mock\Argument\ArgumentInstanceOf;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\SlimPsr15\LazyMiddlewareAdapter;
use Chubbyphp\SlimPsr15\MiddlewareRequestHandlerAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @covers \Chubbyphp\SlimPsr15\LazyMiddlewareAdapter
 */
final class LazyMiddlewareAdapterTest extends TestCase
{
    use MockByCallsTrait;

    public function testHandle()
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockByCalls(ServerRequestInterface::class);

        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockByCalls(ResponseInterface::class, [
            Call::create('withHeader')->with('X-Test', 'test')->willReturnSelf(),
        ]);

        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            $response = $response->withHeader('X-Test', 'test');

            return $response;
        };

        /** @var MiddlewareInterface|MockObject $middleware */
        $middleware = $this->getMockByCalls(MiddlewareInterface::class, [
            Call::create('process')
                ->with(
                    $request,
                    new ArgumentInstanceOf(MiddlewareRequestHandlerAdapter::class)
                )
                ->willReturnCallback(
                    function (ServerRequestInterface $request, MiddlewareRequestHandlerAdapter $requestHandler) {
                        return $requestHandler->handle($request);
                    }
                ),
        ]);

        /** @var ContainerInterface|MockObject $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with('serviceId')->willReturn($middleware),
        ]);

        $adapter = new LazyMiddlewareAdapter($container, 'serviceId');

        self::assertSame($response, $adapter($request, $response, $next));
    }
}
