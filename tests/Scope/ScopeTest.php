<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Scope;

use Dmytrof\FractalBundle\Scope\Scope;
use Dmytrof\FractalBundle\Service\FractalManager;
use Dmytrof\FractalBundle\Tests\Data\TestDataExtension;
use Dmytrof\FractalBundle\Service\ExtensionsContainer;
use League\Fractal\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

class ScopeTest extends TestCase
{
    public function testCreateScope()
    {
        $fractalManager = $this->createMock(FractalManager::class);
        $resource = $this->createMock(ResourceInterface::class);

        $scope = new Scope($fractalManager, $resource, 'test');

        $this->assertInstanceOf(FractalManager::class, $scope->getManager());
        $this->assertInstanceOf(ResourceInterface::class, $scope->getResource());
        $this->assertEquals('test', $scope->getScopeIdentifier());
    }
}