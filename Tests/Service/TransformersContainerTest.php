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

use Dmytrof\FractalBundle\{Exception\TransformerException, Transformer\AbstractTransformer};
use Dmytrof\FractalBundle\Service\{ExtensionsContainer, TransformersContainer};
use PHPUnit\Framework\TestCase;

class TransformersContainerTest extends TestCase
{
    /**
     * @var TestTransformer
     */
    protected $testTransformer;

    public function setUp(): void
    {
        parent::setUp();

        $extensionsContainer = $this->createMock(ExtensionsContainer::class);
        $this->testTransformer = new TestTransformer($extensionsContainer);
    }

    public function testCreateContainer()
    {
        $container = new TransformersContainer([]);
        $this->assertCount(0, $container);
        $this->assertSame(0, $container->count());

        $container2 = new TransformersContainer([$this->testTransformer]);
        $this->assertCount(1, $container2);
        $this->assertSame(1, $container2->count());

        $this->assertInstanceOf(\Iterator::class, $container->getIterator());
    }

    public function testAddTransformer()
    {
        $container = new TransformersContainer([]);
        $container->addTransformer($this->testTransformer);

        $this->expectException(\Error::class);
        $container->addTransformer(new \StdClass());

        $this->assertCount(1, $container);
        $this->assertSame(1, $container->count());

        $this->assertTrue($container->has(TestTransformer::class));
        $this->assertFalse($container->has('SomeClass'));

        $this->assertSame($this->testTransformer, $container->get(TestTransformer::class));
        $this->expectException(TransformerException::class);
        $container->get('SomeClass');
    }
}

class TestTransformer extends AbstractTransformer
{
    public function transformSubject($subject): array
    {
        return [];
    }
}