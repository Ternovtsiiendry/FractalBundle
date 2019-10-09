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

use Dmytrof\FractalBundle\Exception\RuntimeException;
use Dmytrof\FractalBundle\Scope\Scope;
use League\Fractal\{Manager, Resource\ResourceInterface, ScopeFactory as BaseScopeFactory};

class ScopeFactory extends BaseScopeFactory
{
    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return Scope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        if (!$manager instanceof FractalManager) {
            throw new RuntimeException(sprintf('Manager must be instance of %s. Input vas: %s', FractalManager::class, get_class($manager)));
        }
        return new Scope($manager, $resource, $scopeIdentifier);
    }
}