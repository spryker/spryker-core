<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\ServerVariables;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $url
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\ServerVariable[] $variables
 */
class Server extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('url', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('variables', new PropertyDefinition(ServerVariables::class));
    }
}
