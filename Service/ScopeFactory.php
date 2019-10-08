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

use Dmytrof\FractalBundle\Scope\Scope;
use League\Fractal\{Manager, Resource\ResourceInterface, ScopeFactory as BaseScopeFactory};

class ScopeFactory extends BaseScopeFactory
{
    /**
     * @var TransformersContainer
     */
    protected $transformersContainer;

    /**
     * ScopeFactory constructor.
     * @param TransformersContainer $transformersContainer
     */
    public function __construct(TransformersContainer $transformersContainer)
    {
        $this->transformersContainer = $transformersContainer;
    }

    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        return new Scope($manager, $resource, $scopeIdentifier, $this->transformersContainer);
    }
}