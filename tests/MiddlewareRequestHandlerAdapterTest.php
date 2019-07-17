<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\SlimPsr15;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\SlimPsr15\MiddlewareRequestHandlerAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Chubbyphp\SlimPsr15\MiddlewareRequestHandlerAdapter
 *
 * @internal
 */
final class MiddlewareRequestHandlerAdapterTest extends TestCase
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

        $adapter = new MiddlewareRequestHandlerAdapter($response, $next);

        self::assertSame($response, $adapter->handle($request));
    }
}
