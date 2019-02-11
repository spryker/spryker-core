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
 * @property-read string $authorizationUrl
 * @property-read string $tokenUrl
 * @property-read string $refreshUrl
 * @property-read string[] $email
 */
class OAuthFlow extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('authorizationUrl', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('tokenUrl', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('refreshUrl', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('email', new PropertyDefinition(StringEnumeration::class));
    }
}
