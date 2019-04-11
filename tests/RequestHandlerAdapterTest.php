<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\SlimPsr15;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\SlimPsr15\RequestHandlerAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @covers \Chubbyphp\SlimPsr15\RequestHandlerAdapter
 */
final class RequestHandlerAdapterTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockByCalls(ServerRequestInterface::class);

        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockByCalls(ResponseInterface::class);

        /** @var RequestHandlerInterface|MockObject $requestHandler */
        $requestHandler = $this->getMockByCalls(RequestHandlerInterface::class, [
            Call::create('handle')->with($request)->willReturn($response),
        ]);

        $adapter = new RequestHandlerAdapter($requestHandler);

        self::assertSame($response, $adapter($request));
    }
}
