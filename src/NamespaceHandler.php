<?php

namespace Serganbus\Http\GuzzleStubs;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Exception;

/**
 * Фейковый обработчик запросов guzzle.
 *
 * @author Serganbus <sega234@mail.ru>
 */
class NamespaceHandler
{
    /**
     * @var string
     */
    private $rootNs;
    
    /**
     *
     * @param string $rootNs Корневое пространство имен, в котором находятся обработчики ответов
     */
    public function __construct(string $rootNs)
    {
        $this->rootNs = $rootNs;
    }
    
    /**
     * @param RequestInterface $req
     * @param array $options
     * @return FulfilledPromise
     */
    public function __invoke(RequestInterface $req, array $options)
    {
        try {
            $className = $this->getClassName($req);
            $handler = new $className();
            if (!($handler instanceof StubInterface)) {
                throw new \Exception("{$className} should implement \Serganbus\Http\GuzzleStubs\StubInterface");
            }
            
            $response = $handler->handle($req);
        } catch (Exception $ex) {
            $response = new Response(500, ['X-Header' => 'stub'], $ex->getMessage());
        }
        return new FulfilledPromise($response);
    }
    
    public function getClassName(RequestInterface $req): string
    {
        $path = $req->getUri()->getPath();
        $exploded = explode('/', $path);
        $filtered = array_filter($exploded, function (string $item) {
            return !empty($item);
        });
        
        $className = $this->rootNs;
        foreach ($filtered as $item) {
            $className .= '\\' . ucfirst(strtolower($item));
        }
        $className .= '\\' . ucfirst(strtolower($req->getMethod())) . 'Handler';
        
        return $className;
    }
}
