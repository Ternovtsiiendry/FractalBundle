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

use Dmytrof\FractalBundle\Tests\Data\{Foo, FooTransformer, TestDataExtension};
use Dmytrof\FractalBundle\Service\{ExtensionsContainer, FractalManager, ScopeFactory, TransformersContainer};
use PHPUnit\Framework\TestCase;

class FractalManagerTest extends TestCase
{
    /**
     * @var FractalManager
     */
    protected $fractalManager;

    public function setUp(): void
    {
        $extensionContainer = new ExtensionsContainer([new TestDataExtension()]);
        $this->fractalManager = new FractalManager(new TransformersContainer([new FooTransformer($extensionContainer)]), new ScopeFactory());
    }

    public function testUsage()
    {
        $foo = new Foo();
        $foo->bar = 'baz';
        $foo->baz = 'bar';
        $foo->gaz = 'bass';

        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'bar',
            TestDataExtension::TEST_DATA_INCLUDE => Foo::TEST_DATA,
        ], $this->fractalManager->getModelData($foo, FooTransformer::class));

        $transformer = $this->fractalManager->getTransformer(FooTransformer::class)->removeDefaultInclude(TestDataExtension::TEST_DATA_INCLUDE);
        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'bar',
        ], $this->fractalManager->getModelData($foo, $transformer));

        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'bar',
            'boo' => Foo::BOO_DATA,
        ] + Foo::TEST_DATA, (clone $this->fractalManager)->parseIncludes([TestDataExtension::TEST_DATA_INCLUDE, 'boo'])->getModelData($foo, $transformer->addIncludeToRoot(TestDataExtension::TEST_DATA_INCLUDE)));

        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'bar',
            'boo' => Foo::BOO_DATA,
        ] + Foo::TEST_DATA, (clone $this->fractalManager)->parseIncludes([TestDataExtension::TEST_DATA_INCLUDE])->getModelData($foo, $transformer->addDefaultInclude('boo')));
    }
}