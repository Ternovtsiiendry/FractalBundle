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

use Symfony\Component\Config\Definition\{
    Builder\TreeBuilder, ConfigurationInterface
};

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('df_studio_fractal');
        $rootNode = $treeBuilder->root('df_studio_fractal');

        return $treeBuilder;
    }
}
