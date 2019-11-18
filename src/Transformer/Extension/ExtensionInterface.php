<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Transformer\Extension;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;

interface ExtensionInterface
{
    /**
     * Checks if extension is supported by transformer
     * @param \ReflectionClass $reflection
     * @param AbstractTransformer $transformer
     * @return bool
     */
    public function supports(\ReflectionClass $reflection, AbstractTransformer $transformer): bool;

    /**
     * Decorates transformer
     * @param AbstractTransformer $transformer
     */
    public function decorateTransformer(AbstractTransformer $transformer): void;

    /**
     * Transform handler
     * @param $subject
     * @param AbstractTransformer $transformer
     * @return array
     */
    public function transform($subject, AbstractTransformer $transformer): array;
}