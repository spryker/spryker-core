<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\AbstractObject;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\ObjectSpecification;

/**
 * @property-read \SprykerTest\Glue\Testify\OpenApi3\Stub\Bars $bar
 */
class Foo extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('bar', new PropertyDefinition(Bars::class));
    }
}
