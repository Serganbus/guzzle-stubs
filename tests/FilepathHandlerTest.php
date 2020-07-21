<?php

namespace Serganbus\Http\GuzzleStubs\Tests;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Serganbus\Http\GuzzleStubs\FilepathHandler;
use PHPUnit\Framework\TestCase;

/**
 * @author Serganbus <sega234@mail.ru>
 */
class FilepathHandlerTest extends TestCase
{
    /**
     * @var NamespaceHandlerStubber
     */
    private $handler;
    
    public function setUp(): void
    {
        $this->handler = new FilepathHandler($this->getFilePath([__DIR__, '..', 'stubs']));
    }
    
    public function tearDown(): void
    {
        $this->handler = null;
    }
    
    public function invokeDataProvider()
    {
        return [
            [new Request('GET', '/api/1.0/order'), 200],
            [new Request('POST', '/api/1.0/order'), 200],
            [new Request('PUT', '/api/1.0/order'), 200],
            [new Request('DELETE', '/api/1.0/order'), 200],
            [new Request('OPTIONS', '/api/1.0/order'), 500],
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
    
    public function getClassFullPathDataProvider()
    {
        $pieces = [__DIR__, '..', 'stubs'];
        return [
            [new Request('GET', '/api/1.0/jsonrpc'), $this->getFilePath(array_merge($pieces, ['api', '1.0', 'jsonrpc', 'GetHandler.php']))],
            [new Request('GET', '/api/1.0/order'), $this->getFilePath(array_merge($pieces, ['api', '1.0', 'order', 'GetHandler.php']))],
            [new Request('POST', '/api/1.0/order'), $this->getFilePath(array_merge($pieces, ['api', '1.0', 'order', 'PostHandler.php']))],
            [new Request('PUT', '/api/1.0/order'), $this->getFilePath(array_merge($pieces, ['api', '1.0', 'order', 'PutHandler.php']))],
            [new Request('DELETE', '/api/1.0/order'), $this->getFilePath(array_merge($pieces, ['api', '1.0', 'order', 'DeleteHandler.php']))],
        ];
    }
    
    /**
     * @dataProvider getClassFullPathDataProvider
     */
    public function testGetClassFullPath(RequestInterface $req, string $expectedClassFullPath)
    {
        $actualClassFullPath = $this->handler->getClassFullPath($req);
        $this->assertEquals($expectedClassFullPath, $actualClassFullPath);
    }
    
    public function getClassNameDataProvider()
    {
        return [
            [new Request('GET', '/api/1.0/jsonrpc'), 'GetHandler'],
            [new Request('GET', '/api/1.0/order'), 'GetHandler'],
            [new Request('POST', '/api/1.0/order'), 'PostHandler'],
            [new Request('PUT', '/api/1.0/order'), 'PutHandler'],
            [new Request('DELETE', '/api/1.0/order'), 'DeleteHandler'],
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
    
    private function getFilePath(array $pieces)
    {
        return implode(DIRECTORY_SEPARATOR, $pieces);
    }
}
