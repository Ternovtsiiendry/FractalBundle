<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\ViewHandler;

use Dmytrof\FractalBundle\Service\FractalManager;
use FOS\RestBundle\View\{View, ViewHandler};
use Symfony\Component\HttpFoundation\{Request, Response};

class FractalHandler
{
    public const ATTRIBUTE = FractalManager::CODE;

    public function __invoke(ViewHandler $viewHandler, View $view, Request $request, $format)
    {
       if ($view->getContext()->getAttribute(static::ATTRIBUTE)) {

           $data = $view->getData();
           $view->setData(null);

           $response = $viewHandler->createResponse($view, $request, $format);
           if (null !== $data) {
               $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
               $response->setStatusCode(Response::HTTP_OK);
           }
           return $response;
       }

       return $viewHandler->createResponse($view, $request, $format);
    }
}