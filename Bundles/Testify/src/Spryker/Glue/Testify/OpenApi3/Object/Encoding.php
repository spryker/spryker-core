<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Headers;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $contentType
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Header[] $headers
 * @property-read string $style
 * @property-read bool $explode
 * @property-read bool $allowReserved
 * @property-read string $version
 */
class Encoding extends AbstractObject
{
    /**
     * @inheritdoc
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
