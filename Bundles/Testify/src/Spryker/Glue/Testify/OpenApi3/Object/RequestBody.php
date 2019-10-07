<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\MediaTypes;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\MediaType[] $content
 * @property-read bool $required
 */
class RequestBody extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('content', new PropertyDefinition(MediaTypes::class))
            ->setProperty('required', new PropertyDefinition(BoolPrimitive::class));
    }
}
