<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $description
 * @property-read string $url
 */
class ExternalDocumentation extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('url', (new PropertyDefinition(StringPrimitive::class))->setRequired(true));
    }
}
