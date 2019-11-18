<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\DependencyInjection\Compiler;

use Dmytrof\FractalBundle\ViewHandler\FractalHandler;
use Symfony\Component\DependencyInjection\{
    Compiler\CompilerPassInterface, ContainerBuilder, Reference
};

class FOSRestPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        
        if (isset($bundles['FOSRestBundle'])) {
            $definition = $container->getDefinition('fos_rest.view_handler.default');
            $definition->addMethodCall('registerHandler', ['json', new Reference(FractalHandler::class)]);
        }
    }
}