<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\SlimPsr15;

use Chubbyphp\Mock\Argument\ArgumentInstanceOf;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\SlimPsr15\MiddlewareAdapter;
use Chubbyphp\SlimPsr15\MiddlewareRequestHandlerAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @covers \Chubbyphp\SlimPsr15\MiddlewareAdapter
 *
 * @internal
 */
final class MiddlewareAdapterTest extends TestCase
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
            return $response->withHeader('X-Test', 'test');
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

        $adapter = new MiddlewareAdapter($middleware);

        self::assertSame($response, $adapter($request, $response, $next));
    }
}
