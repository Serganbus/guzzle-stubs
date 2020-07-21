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
class FilepathHandler
{
    /**
     * @var string
     */
    private $rootDir;
    
    /**
     *
     * @param string $rootDir Корневая директория, в котором находятся обработчики ответов
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = trim($rootDir, '/');
    }
    
    /**
     * @param RequestInterface $req
     * @param array $options
     * @return FulfilledPromise
     */
    public function __invoke(RequestInterface $req, array $options)
    {
        try {
            $classFullPath = $this->getClassFullPath($req);
            require $classFullPath;
            
            $className = $this->getClassName($req);
            $handler = new $className();
            if (!($handler instanceof StubInterface)) {
                throw new \Exception("{$classFullPath} should implement \Serganbus\Http\GuzzleStubs\StubInterface");
            }
            
            $response = $handler->handle($req);
        } catch (Exception $ex) {
            $response = new Response(500, ['X-Header' => 'stub'], $ex->getMessage());
        }
        return new FulfilledPromise($response);
    }
    
    public function getClassFullPath(RequestInterface $req): string
    {
        $path = $req->getUri()->getPath();
        $exploded = explode('/', $path);
        $filtered = array_filter($exploded, function (string $item) {
            return !empty($item);
        });
        
        $className = $this->rootDir;
        foreach ($filtered as $item) {
            $className .= DIRECTORY_SEPARATOR . strtolower($item);
        }
        $className .= DIRECTORY_SEPARATOR . $this->getClassName($req) . '.php';
        
        return $className;
    }
    
    public function getClassName(RequestInterface $req): string
    {
        return ucfirst(strtolower($req->getMethod())) . 'Handler';
    }
}
