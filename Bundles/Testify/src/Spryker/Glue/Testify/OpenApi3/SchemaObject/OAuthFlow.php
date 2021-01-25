<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

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
     * @inheritDoc
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
