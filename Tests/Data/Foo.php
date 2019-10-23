<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Tests\Data;

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