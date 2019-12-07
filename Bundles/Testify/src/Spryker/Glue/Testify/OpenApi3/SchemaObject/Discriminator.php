<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $name
 * @property-read string $namespace
 * @property-read string $prefix
 * @property-read bool $attribute
 * @property-read bool $wrapped
 */
class Discriminator extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('name', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('namespace', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('prefix', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('attribute', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('wrapped', new PropertyDefinition(BoolPrimitive::class));
    }
}
