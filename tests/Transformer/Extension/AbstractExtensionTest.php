<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Transformer\Extension;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\FractalBundle\Tests\Data\{Foo, TestDataExtension};
use Dmytrof\FractalBundle\Service\ExtensionsContainer;
use PHPUnit\Framework\TestCase;

class AbstractExtensionTest extends TestCase
{
    /**
     * @var TestDataExtension
     */
    protected $extension;

    public function setUp(): void
    {
        $this->extension = new TestDataExtension();
    }

    public function testCreatedTransformer()
    {
        $transformer = $this->createMock(AbstractTransformer::class);
        $this->assertTrue($this->extension->supports(new \ReflectionClass(new Foo()), $transformer));
        $this->assertFalse($this->extension->supports(new \ReflectionClass(new \StdClass()), $transformer));
    }

    public function testTransform()
    {
        $transformer = $this->createMock(AbstractTransformer::class);
        $this->assertIsArray($this->extension->transform(new Foo(), $transformer));
        $this->assertEmpty($this->extension->transform(new Foo(), $transformer));

        $this->assertEmpty($this->extension->decorateTransformer($transformer));
    }
}