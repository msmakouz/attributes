<?php

/**
 * This file is part of Spiral Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\Attributes\Reader;

use Spiral\Attributes\ReaderInterface;

class MergeReader extends Composite
{
    /**
     * {@inheritDoc}
     */
    public function getClassMetadata(\ReflectionClass $class, string $name = null): iterable
    {
        return $this->merge(static function (ReaderInterface $reader) use ($class, $name): iterable {
            return $reader->getClassMetadata($class, $name);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodMetadata(\ReflectionMethod $method, string $name = null): iterable
    {
        return $this->merge(static function (ReaderInterface $reader) use ($method, $name): iterable {
            return $reader->getMethodMetadata($method, $name);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyMetadata(\ReflectionProperty $property, string $name = null): iterable
    {
        return $this->merge(static function (ReaderInterface $reader) use ($property, $name): iterable {
            return $reader->getPropertyMetadata($property, $name);
        });
    }

    /**
     * @psalm-param callable(ReaderInterface): list<array-key, object> $resolver
     *
     * @param callable $resolver
     * @return iterable
     */
    private function merge(callable $resolver): iterable
    {
        foreach ($this->readers as $reader) {
            yield from $resolver($reader);
        }
    }
}
