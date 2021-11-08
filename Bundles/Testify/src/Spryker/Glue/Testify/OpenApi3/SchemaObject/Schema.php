<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Schemas;
use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\Enumeration;
use Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\IntPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringEnumeration;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $title
 * @property-read \Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive $multipleOf
 * @property-read \Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive $maximum
 * @property-read \Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive $exclusiveMaximum
 * @property-read \Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive $minimum
 * @property-read \Spryker\Glue\Testify\OpenApi3\Primitive\FloatPrimitive $exclusiveMinimum
 * @property-read int $maxLength
 * @property-read int $minLength
 * @property-read string $pattern
 * @property-read int $maxItems
 * @property-read int $minItems
 * @property-read bool $uniqueItems
 * @property-read int $maxProperties
 * @property-read int $minProperties
 * @property-read array<string> $required
 * @property-read array $enum
 * @property-read string $type
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Schemas $allOf
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Schemas $oneOf
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Schemas $anyOf
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Schema $not
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Schema $items
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Schemas $properties
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Schema|bool $additionalProperties // TODO Can be FALSE that restrict other properties
 * @property-read string $description
 * @property-read string $format
 * @property-read mixed $default
 * @property-read bool $nullable
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Discriminator $discriminator
 * @property-read bool $readOnly
 * @property-read bool $writeOnly
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Xml $xml
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\ExternalDocumentation $externalDocs
 * @property-read mixed $example
 * @property-read bool $deprecated
 */
class Schema extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('title', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('multipleOf', new PropertyDefinition(FloatPrimitive::class))
            ->setProperty('maximum', new PropertyDefinition(FloatPrimitive::class))
            ->setProperty('exclusiveMaximum', new PropertyDefinition(FloatPrimitive::class))
            ->setProperty('minimum', new PropertyDefinition(FloatPrimitive::class))
            ->setProperty('exclusiveMinimum', new PropertyDefinition(FloatPrimitive::class))
            ->setProperty('maxLength', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('minLength', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('pattern', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('maxItems', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('minItems', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('uniqueItems', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('maxProperties', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('minProperties', new PropertyDefinition(IntPrimitive::class))
            ->setProperty('required', new PropertyDefinition(StringEnumeration::class))
            ->setProperty('enum', new PropertyDefinition(Enumeration::class))
            ->setProperty('type', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('allOf', new PropertyDefinition(Schemas::class))
            ->setProperty('oneOf', new PropertyDefinition(Schemas::class))
            ->setProperty('anyOf', new PropertyDefinition(Schemas::class))
            ->setProperty('not', new PropertyDefinition(static::class))
            ->setProperty('items', new PropertyDefinition(static::class))
            ->setProperty('properties', new PropertyDefinition(Schemas::class))
            ->setProperty('additionalProperties', new PropertyDefinition(static::class))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('format', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('default', new PropertyDefinition(Any::class))
            ->setProperty('nullable', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('discriminator', new PropertyDefinition(Discriminator::class))
            ->setProperty('readOnly', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('writeOnly', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('xml', new PropertyDefinition(Xml::class))
            ->setProperty('externalDocs', new PropertyDefinition(ExternalDocumentation::class))
            ->setProperty('example', new PropertyDefinition(Any::class))
            ->setProperty('deprecated', new PropertyDefinition(BoolPrimitive::class));
    }
}
