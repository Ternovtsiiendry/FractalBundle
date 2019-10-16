<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Service;

use Dmytrof\FractalBundle\Exception\TransformerException;
use Dmytrof\FractalBundle\Serializer\SimpleArraySerializer;
use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use League\Fractal\{Resource\Collection, Resource\Item, ScopeFactoryInterface, Manager as BaseManager};

class FractalManager extends BaseManager
{
    public const CODE = 'fractal';

    /**
     * @var TransformersContainer
     */
    protected $transformersContainer;

    /**
     * FractalManager constructor.
     * @param TransformersContainer $transformersContainer
     * @param ScopeFactory|null $scopeFactory
     */
    public function __construct(TransformersContainer $transformersContainer, ScopeFactory $scopeFactory)
    {
        parent::__construct($scopeFactory);
        $this->setSerializer(new SimpleArraySerializer());
        $this->transformersContainer = $transformersContainer;
    }

    /**
     * Returns paginator data
     * @param iterable $iterator
     * @param $transformer
     * @return array
     */
    public function getPaginatorData(iterable $iterator, $transformer): array
    {
        return $this->createData(new Collection($iterator, $transformer))->toArray();
    }

    /**
     * Returns model data
     * @param $model
     * @param $transformer
     * @return array
     */
    public function getModelData($model, $transformer): array
    {
        return $this->createData(new Item($model, $transformer))->toArray();
    }

    /**
     * Returns transformer
     * @param string $transformerClass
     * @return AbstractTransformer
     */
    public function getTransformer(string $transformerClass): AbstractTransformer
    {
        return $this->transformersContainer->get($transformerClass);
    }

    /**
     * Returns collection data
     * @param iterable $data
     * @param $transformer
     * @return array
     */
    public function getCollectionData(iterable $data, $transformer): array
    {
        return $this->createData(new Collection($data, $transformer))->toArray();
    }

    /**
     * Returns transformers for subject
     * @param $subject
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransformersForSubject($subject): \Doctrine\Common\Collections\Collection
    {
        $collection = new ArrayCollection();
        /** @var AbstractTransformer $transformer */
        foreach ($this->transformersContainer as $transformer) {
            if ($transformer->supports($subject)) {
                $collection->add($transformer);
            }
        }
        if ($collection->count()) {
            return $collection;
        }
        throw new TransformerException(sprintf('There is no transformer for subject %s', is_scalar($subject) ? $subject : get_class($subject)));
    }
}