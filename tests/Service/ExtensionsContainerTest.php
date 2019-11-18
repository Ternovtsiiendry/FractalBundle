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

use Dmytrof\FractalBundle\Tests\Data\TestDataExtension;
use Dmytrof\FractalBundle\Service\ExtensionsContainer;
use PHPUnit\Framework\TestCase;

class ExtensionsContainerTest extends TestCase
{
    /**
     * @var TestDataExtension
     */
    protected $testExtension;

    public function setUp(): void
    {
        parent::setUp();

        $this->testExtension = new TestDataExtension();
    }

    public function testCreateContainer()
    {
        $container = new ExtensionsContainer([]);
        $this->assertCount(0, $container);

        $container2 = new ExtensionsContainer([$this->testExtension]);
        $this->assertCount(1, $container2);

        $this->assertInstanceOf(\Iterator::class, $container->getIterator());
    }

    public function testAddTransformer()
    {
        $container = new ExtensionsContainer([]);
        $container->addExtension($this->testExtension);

        $this->assertCount(1, $container);

        $this->expectException(\Error::class);
        $container->addExtension(new \StdClass());
    }
}