<?php

use Serganbus\Http\GuzzleStubs\StubInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

/**
 * @author Serganbus <sega234@mail.ru>
 */
class DeleteHandler implements StubInterface
{
    public function handle(RequestInterface $request): ResponseInterface
    {
        return new Response();
    }
}
