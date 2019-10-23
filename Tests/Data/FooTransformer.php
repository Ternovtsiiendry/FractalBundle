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

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use League\Fractal\Resource\ResourceInterface;

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