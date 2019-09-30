<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringEnumeration;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string[] $enum
 * @property-read string $default
 * @property-read string $description
 */
class ServerVariable extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('enum', new PropertyDefinition(StringEnumeration::class))
            ->setProperty('default', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class));
    }
}
