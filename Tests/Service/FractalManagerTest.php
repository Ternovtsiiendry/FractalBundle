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

use Dmytrof\FractalBundle\Service\{ExtensionsContainer, FractalManager, ScopeFactory, TransformersContainer};
use Dmytrof\FractalBundle\Transformer\{AbstractTransformer, Extension\AbstractExtension};
use League\Fractal\Resource\{Primitive, ResourceInterface};
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

interface TestDataInterface
{
    public function getTestData(): array;
}

class Foo implements TestDataInterface
{
    public const TEST_DATA = [
        'hello' => 'world',
    ];
    public const BOO_DATA = ['boo1', 'boo2', 'boo3'];
    public $bar;
    public $baz;
    public $gaz;

    public function getTestData(): array
    {
        return self::TEST_DATA;
    }

    public function getBooData(): array
    {
        return self::BOO_DATA;
    }
}

class TestDataExtension extends AbstractExtension
{
    public const TEST_DATA_INCLUDE = 'testData';

    protected function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return $reflectionClass->implementsInterface(TestDataInterface::class);
    }

    public function decorateTransformer(AbstractTransformer $transformer): void
    {
        $transformer
            ->addAvailableInclude(self::TEST_DATA_INCLUDE)
            ->addDefaultInclude(self::TEST_DATA_INCLUDE)
            ->setIncludeCall(self::TEST_DATA_INCLUDE, [$this, 'includeTestData'])
        ;
    }

    public function includeTestData(TestDataInterface $subject, AbstractTransformer $transformer): ResourceInterface
    {
        return new Primitive($subject->getTestData());
    }
}

class FooTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = Foo::class;

    protected $availableIncludes = [
        'boo',
    ];

    public function transformSubject($subject): array
    {
        return [
            'bar' => $subject->bar,
            'baz' => $subject->baz,
        ];
    }

    public function includeBoo(Foo $subject): ResourceInterface
    {
        return $this->primitive($subject->getBooData());
    }
}