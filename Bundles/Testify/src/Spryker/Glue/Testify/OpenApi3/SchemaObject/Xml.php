<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $name
 * @property-read string $url
 * @property-read string $email
 */
class Xml extends AbstractObject
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
