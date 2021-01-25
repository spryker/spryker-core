<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Headers;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $contentType
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Header[] $headers
 * @property-read string $style
 * @property-read bool $explode
 * @property-read bool $allowReserved
 * @property-read string $version
 */
class Encoding extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('contentType', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('headers', new PropertyDefinition(Headers::class))
            ->setProperty('style', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('explode', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('allowReserved', new PropertyDefinition(BoolPrimitive::class));
    }
}
