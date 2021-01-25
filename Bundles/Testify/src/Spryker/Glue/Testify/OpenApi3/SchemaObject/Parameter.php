<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Examples;
use Spryker\Glue\Testify\OpenApi3\Collection\MediaTypes;
use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $name
 * @property-read string $in
 * @property-read string $description
 * @property-read bool $required
 * @property-read bool $deprecated
 * @property-read bool $allowEmptyValue
 * @property-read string $style
 * @property-read bool $explode
 * @property-read bool $allowReserved
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Schema $schema
 * @property-read mixed $example
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Example[] $examples
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\MediaType[] $content
 */
class Parameter extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('name', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('in', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('required', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('deprecated', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('allowEmptyValue', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('style', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('explode', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('allowReserved', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('schema', new PropertyDefinition(Schema::class))
            ->setProperty('example', new PropertyDefinition(Any::class))
            ->setProperty('examples', new PropertyDefinition(Examples::class))
            ->setProperty('content', new PropertyDefinition(MediaTypes::class));
    }
}
