<?php

namespace Serganbus\Http\GuzzleStubs;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Интерфейс для получения стабовых ответов
 *
 * @author Serganbus <sega234@mail.ru>
 */
interface StubInterface
{
    public function handle(RequestInterface $request): ResponseInterface;
}
