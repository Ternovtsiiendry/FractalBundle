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

abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * @var array
     */
    protected $supports = [];

    /**
     * {@inheritdoc}
     */
    public function supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        $subjectClass = $reflectionClass->getName();
        if (!isset($this->supports[$subjectClass])) {
            $this->supports[$subjectClass] = $this->_supports($reflectionClass, $transformer);
        }
        return $this->supports[$subjectClass];
    }

    /**
     * Checks if extension is supported by transformer
     * @param \ReflectionClass $reflectionClass
     * @param AbstractTransformer $transformer
     * @return bool
     */
    protected function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function decorateTransformer(AbstractTransformer $transformer): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function transform($subject, AbstractTransformer $transformer): array
    {
        return [];
    }
}