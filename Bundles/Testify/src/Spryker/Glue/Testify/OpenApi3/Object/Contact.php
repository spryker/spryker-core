<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $name
 * @property-read string $url
 * @property-read string $email
 */
class Contact extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('name', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('url', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('email', new PropertyDefinition(StringPrimitive::class));
    }
}
