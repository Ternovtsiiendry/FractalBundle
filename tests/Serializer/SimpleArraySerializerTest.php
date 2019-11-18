<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Serializer;

use Dmytrof\FractalBundle\Serializer\SimpleArraySerializer;
use PHPUnit\Framework\TestCase;

class SimpleArraySerializerTest extends TestCase
{
    /**
     * @dataProvider getDataProvider
     * @param $resourceKey
     * @param array $data
     * @param array $result
     */
    public function testSerializeCollection($resourceKey, array $data, array $result)
    {
        $serializer = new SimpleArraySerializer();

        $this->assertEquals($result, $serializer->collection($resourceKey, $data));
    }

    public function getDataProvider(): array
    {
        return [
            [
                'test',
                ['foo' => 'bar'],
                ['test' => ['foo' => 'bar']],
            ],
            [
                1,
                ['foo' => 'bar'],
                [1 => ['foo' => 'bar']],
            ],
            [
                '',
                ['foo' => 'bar'],
                ['foo' => 'bar'],
            ],
            [
                0,
                ['foo' => 'bar'],
                ['foo' => 'bar'],
            ],
            [
                null,
                ['foo' => 'bar'],
                ['foo' => 'bar'],
            ]
        ];
    }
}