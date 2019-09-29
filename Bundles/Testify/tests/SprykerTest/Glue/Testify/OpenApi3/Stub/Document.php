<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Object\AbstractObject;
use Spryker\Glue\Testify\OpenApi3\Object\ObjectSpecification;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read \SprykerTest\Glue\Testify\OpenApi3\Stub\Foo $foo1
 * @property-read \SprykerTest\Glue\Testify\OpenApi3\Stub\Foo $foo2
 */
class Document extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('foo1', new PropertyDefinition(Foo::class))
            ->setProperty('foo2', new PropertyDefinition(Foo::class));
    }
}
