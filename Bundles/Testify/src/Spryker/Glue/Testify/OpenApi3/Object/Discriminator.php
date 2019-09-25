<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

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
