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

use Dmytrof\FractalBundle\{Exception\TransformerException, Service\FractalManager, Transformer\AbstractTransformer};
use League\Fractal\{Resource\ResourceInterface, Scope as BaseScope, TransformerAbstract};

class Scope extends BaseScope
{
    /**
     * Scope constructor.
     * @param FractalManager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     */
    public function __construct(FractalManager $manager, ResourceInterface $resource, ?string $scopeIdentifier = null)
    {
        parent::__construct($manager, $resource, $scopeIdentifier);
    }

    /**
     * Returns manager
     * @return FractalManager
     */
    public function getManager(): FractalManager
    {
        return parent::getManager();
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
            $transformer = $this->getTransformer($transformer);
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
     * @param string $transformerClass
     * @return AbstractTransformer|null
     */
    protected function getTransformer(string $transformerClass): ?AbstractTransformer
    {
        try {
            return $this->getManager()->getTransformer($transformerClass);
        } catch (TransformerException $e) {
            return null;
        }
    }
}