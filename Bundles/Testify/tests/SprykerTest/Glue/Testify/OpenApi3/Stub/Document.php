<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\AbstractObject;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\ObjectSpecification;

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
