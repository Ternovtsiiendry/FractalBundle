<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Service;

use Dmytrof\FractalBundle\Scope\Scope;
use Dmytrof\FractalBundle\Service\{FractalManager, ScopeFactory};
use League\Fractal\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

class ScopeFactoryTest extends TestCase
{
    public function testCreateScope()
    {
        $scopeFactory = new ScopeFactory();

        $manager = $this->createMock(FractalManager::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->assertInstanceOf(Scope::class, $scopeFactory->createScopeFor($manager, $resource));
    }
}