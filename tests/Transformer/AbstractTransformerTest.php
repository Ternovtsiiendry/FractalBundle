<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Transformer;

use Dmytrof\FractalBundle\Tests\Data\{Foo, FooTransformer, TestDataExtension, TestExtension};
use Dmytrof\FractalBundle\Service\ExtensionsContainer;
use PHPUnit\Framework\TestCase;

class AbstractTransformerTest extends TestCase
{
    /**
     * @var FooTransformer
     */
    protected $transformer;

    public function setUp(): void
    {
        $extensionContainer = new ExtensionsContainer([new TestExtension(), new TestDataExtension()]);
        $this->transformer = new FooTransformer($extensionContainer);
        $this->transformer->setup();
    }

    public function testCreatedTransformer()
    {
        $this->assertInstanceOf(ExtensionsContainer::class, $this->transformer->getExtensionsContainer());
        $this->assertCount(2, $this->transformer->getExtensionsContainer());
        $this->assertCount(1, $this->transformer->getExtensions());
        $this->assertEquals(Foo::class, $this->transformer->getSubjectClass());

        $this->assertCount(2, $this->transformer->getAvailableIncludes());
        $this->assertCount(1, $this->transformer->getDefaultIncludes());
    }

    public function testTransform()
    {
        $foo = new Foo();
        $foo->bar = 'baz';
        $foo->baz = 'jazz';

        $this->assertTrue($this->transformer->supports($foo));
        $this->assertFalse($this->transformer->supports(new \StdClass()));

        $this->assertEquals([
            'bar' => 'baz',
            'baz' => 'jazz',
        ], $this->transformer->transform($foo));
    }

    public function testAvailableIncludes()
    {
        $this->transformer->addAvailableInclude('bar', 'baz');
        $this->assertCount(4, $this->transformer->getAvailableIncludes());
        $this->assertEquals(['boo', TestDataExtension::TEST_DATA_INCLUDE, 'bar', 'baz'], $this->transformer->getAvailableIncludes());

        $this->transformer->removeAvailableInclude('baz', 'bar');
        $this->assertEquals(['boo', TestDataExtension::TEST_DATA_INCLUDE], $this->transformer->getAvailableIncludes());

        $includes = ['foo', 'bar', 'baz'];
        $this->assertEquals($includes, $this->transformer->setAvailableIncludes($includes)->getAvailableIncludes());
    }

    public function testDefaultIncludes()
    {
        $this->transformer->addDefaultInclude('bar', 'baz');
        $this->assertCount(3, $this->transformer->getDefaultIncludes());
        $this->assertEquals([TestDataExtension::TEST_DATA_INCLUDE, 'bar', 'baz'], $this->transformer->getDefaultIncludes());

        $this->transformer->removeDefaultInclude('baz', 'bar');
        $this->assertEquals([TestDataExtension::TEST_DATA_INCLUDE], $this->transformer->getDefaultIncludes());

        $includes = ['foo', 'bar', 'baz'];
        $this->assertEquals($includes, $this->transformer->setDefaultIncludes($includes)->getDefaultIncludes());
        $this->assertEquals([], $this->transformer->resetDefaultIncludes()->getDefaultIncludes());
    }

    public function testIncludeCalls()
    {
        $this->assertCount(1, $this->transformer->getIncludeCalls());

        $this->transformer->setIncludeCall('bar', function () {return 'bar';});
        $this->assertCount(2,  $this->transformer->getIncludeCalls());

        $calls = [
            'bar' =>  function () {return 'bar';},
            'baz' =>  function () {return 'baz';},
        ];
        $this->assertEquals($calls, $this->transformer->setIncludeCalls($calls)->getIncludeCalls());
        $this->assertTrue($this->transformer->hasIncludeCall('bar'));
        $this->assertFalse($this->transformer->hasIncludeCall('test'));
        $this->assertSame($calls['baz'], $this->transformer->getIncludeCall('baz'));
        $this->assertNull($this->transformer->getIncludeCall('test'));

        $this->transformer->removeIncludeCall('bar');
        $this->assertFalse($this->transformer->hasIncludeCall('bar'));
        $this->assertCount(1, $this->transformer->getIncludeCalls());
    }

    public function testIncludeToRoot()
    {
        $this->assertCount(0, $this->transformer->getIncludesToRoot());

        $this->transformer->setIncludesToRoot(['bar', 'baz']);
        $this->assertCount(2,  $this->transformer->getIncludesToRoot());
        $this->assertEquals(['bar', 'baz'], $this->transformer->getIncludesToRoot());

        $this->assertEquals(['bar', 'baz', 'bar1', 'baz1'], $this->transformer->addIncludeToRoot('bar1', 'baz1')->getIncludesToRoot());
        $this->assertTrue($this->transformer->hasIncludeToRoot('bar1'));
        $this->assertFalse($this->transformer->removeIncludeToRoot('bar1', 'baz1')->hasIncludeToRoot('bar1'));
    }

    public function testTransformDateTime()
    {
        $dateTime = new \DateTime();
        $this->assertIsString($this->transformer->transformDateTime($dateTime));
        $this->assertNull($this->transformer->transformDateTime(null));
    }
}