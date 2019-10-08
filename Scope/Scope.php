<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Scope;

use Dmytrof\FractalBundle\Service\TransformersContainer;
use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use League\Fractal\{Manager, Resource\ResourceInterface, Scope as BaseScope, TransformerAbstract};

class Scope extends BaseScope
{
    /**
     * @var TransformersContainer
     */
    protected $transformersContainer;

    /**
     * Scope constructor.
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @param TransformersContainer|null $transformersContainer
     */
    public function __construct(Manager $manager, ResourceInterface $resource, ?string $scopeIdentifier = null, ?TransformersContainer $transformersContainer = null)
    {
        parent::__construct($manager, $resource, $scopeIdentifier);
        $this->transformersContainer = $transformersContainer;
    }

    /**
     * @return TransformersContainer
     */
    protected function getTransformersContainer(): ?TransformersContainer
    {
        return $this->transformersContainer;
    }

    /**
     * @param TransformerAbstract|callable|string $transformer
     * @param mixed                        $data
     * @return array
     */
    protected function fireTransformer($transformer, $data)
    {
        if (is_null($data)) {
            return [[],[]];
        }
        if (is_string($transformer)) {
            $transformer = $this->getTransformerService($transformer);
        }
        list($transformedData, $includedData) = parent::fireTransformer($transformer, $data);
        if ($transformer instanceof AbstractTransformer && $transformer->getIncludesToRoot()) {
            $includesToRoot = array_flip($transformer->getIncludesToRoot());
            $dataToRoot = array_intersect_key($transformedData, $includesToRoot);
            $transformedData = array_diff_key($transformedData, $includesToRoot);
            $transformedData = array_merge($transformedData, ...array_values($dataToRoot));
        }
        ksort($transformedData);
        return [$transformedData, $includedData];
    }

    /**
     * Returns transformer service
     * @param string $transformer
     * @return AbstractTransformer|null
     */
    protected function getTransformerService(string $transformer): ?AbstractTransformer
    {
        if ($this->getTransformersContainer() && $this->getTransformersContainer()->has($transformer)) {
            return $this->getTransformersContainer()->get($transformer);
        }
        return null;
    }
}