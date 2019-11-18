<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Data;

use Dmytrof\FractalBundle\Transformer\{AbstractTransformer, Extension\AbstractExtension};

class TestExtension extends AbstractExtension
{
    protected function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return false;
    }
}