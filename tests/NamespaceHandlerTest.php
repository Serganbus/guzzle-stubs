<?php

namespace Serganbus\Http\GuzzleStubs\Tests;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Serganbus\Http\GuzzleStubs\NamespaceHandler;
use PHPUnit\Framework\TestCase;

/**
 * @author Serganbus <sega234@mail.ru>
 */
class NamespaceHandlerTest extends TestCase
{
    /**
     * @var NamespaceHandlerStubber
     */
    private $handler;
    
    public function setUp(): void
    {
        $this->handler = new NamespaceHandler('Serganbus\Http\GuzzleStubs\Tests');
    }
    
    public function tearDown(): void
    {
        $this->handler = null;
    }
    
    public function invokeDataProvider()
    {
        return [
            [new Request('GET', '/api/v1/order'), 200],
            [new Request('POST', '/api/v1/order'), 200],
            [new Request('PUT', '/api/v1/order'), 200],
            [new Request('DELETE', '/api/v1/order'), 200],
            [new Request('OPTIONS', '/api/v1/order'), 500],
        ];
    }
    
    /**
     * @dataProvider invokeDataProvider
     */
    public function test__invoke(RequestInterface $req, int $expectedHttpCode)
    {
        $promise = $this->handler->__invoke($req, []);
        $response = $promise->wait();
        $this->assertEquals($expectedHttpCode, $response->getStatusCode());
    }
    
    public function getClassNameDataProvider()
    {
        return [
            [new Request('GET', '/api/v1/jsonrpc'), 'Serganbus\Http\GuzzleStubs\Tests\Api\V1\Jsonrpc\GetHandler'],
            [new Request('GET', '/api/v1/order'), 'Serganbus\Http\GuzzleStubs\Tests\Api\V1\Order\GetHandler'],
            [new Request('POST', '/api/v1/order'), 'Serganbus\Http\GuzzleStubs\Tests\Api\V1\Order\PostHandler'],
            [new Request('PUT', '/api/v1/order'), 'Serganbus\Http\GuzzleStubs\Tests\Api\V1\Order\PutHandler'],
            [new Request('DELETE', '/api/v1/order'), 'Serganbus\Http\GuzzleStubs\Tests\Api\V1\Order\DeleteHandler'],
        ];
    }
    
    /**
     * @dataProvider getClassNameDataProvider
     */
    public function testGetClassName(RequestInterface $req, string $expectedClassName)
    {
        $actualClassName = $this->handler->getClassName($req);
        $this->assertEquals($expectedClassName, $actualClassName);
    }
}
