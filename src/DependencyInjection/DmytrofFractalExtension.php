<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\DependencyInjection;

use Dmytrof\FractalBundle\Transformer\{AbstractTransformer, Extension\ExtensionInterface};
use Symfony\Component\DependencyInjection\{ContainerBuilder, Loader};
use Symfony\Component\{Config\FileLocator, HttpKernel\DependencyInjection\Extension};

class DmytrofFractalExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->registerForAutoconfiguration(AbstractTransformer::class)
            ->addTag('dmytrof.fractal.transformer');

        $container->registerForAutoconfiguration(ExtensionInterface::class)
            ->addTag('dmytrof.fractal.transformer.extension');
    }
}
