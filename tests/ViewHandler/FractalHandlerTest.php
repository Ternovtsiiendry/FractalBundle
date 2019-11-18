<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\ViewHandler;

use Dmytrof\FractalBundle\ViewHandler\FractalHandler;
use Symfony\Component\HttpFoundation\{Request, Response};
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\{View, ViewHandler};
use PHPUnit\Framework\TestCase;

class FractalHandlerTest extends TestCase
{
    public function testHandler()
    {
        $viewHandler = $this->createMock(ViewHandler::class);
        $viewHandler->method('createResponse')->willReturn(new Response());
        $request = $this->createMock(Request::class);
        $format = 'json';

        $data = ['foo' => 'bar', 'test' => 4];
        $view = new View($data, 200);
        $view->setContext((new Context())->setAttribute(FractalHandler::ATTRIBUTE, true));

        $response = (new FractalHandler())($viewHandler, $view, $request, $format);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('{"foo":"bar","test":4}', $response->getContent());
    }
}