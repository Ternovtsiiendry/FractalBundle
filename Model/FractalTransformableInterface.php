<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Model;

interface FractalTransformableInterface
{
    /**
     * Returns fractal transformer class
     * @return string
     */
    public static function getFractalTransformerClass(): string;
}