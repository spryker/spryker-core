<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

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
 * @property-read FloatPrimitive $multipleOf
 * @property-read FloatPrimitive $maximum
 * @property-read FloatPrimitive $exclusiveMaximum
 * @property-read FloatPrimitive $minimum
 * @property-read FloatPrimitive $exclusiveMinimum
 * @property-read int $maxLength
 * @property-read int $minLength
 * @property-read string $pattern
 * @property-read int $maxItems
 * @property-read int $minItems
 * @property-read bool $uniqueItems
 * @property-read int $maxProperties
 * @property-read int $minProperties
 * @property-read string[] $required
 * @property-read array $enum
 * @property-read string $type
 * @property-read Schemas $allOf
 * @property-read Schemas $oneOf
 * @property-read Schemas $anyOf
 * @property-read Schema $not
 * @property-read Schema $items
 * @property-read Schemas $properties
 * @property-read Schema|bool $additionalProperties // TODO Can be FALSE that restrict other properties
 * @property-read string $description
 * @property-read string $format
 * @property-read mixed $default
 * @property-read bool $nullable
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Discriminator $discriminator
 * @property-read bool $readOnly
 * @property-read bool $writeOnly
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Xml $xml
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\ExternalDocumentation $externalDocs
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
            ->setProperty('not', new PropertyDefinition(self::class))
            ->setProperty('items', new PropertyDefinition(self::class))
            ->setProperty('properties', new PropertyDefinition(Schemas::class))
            ->setProperty('additionalProperties', new PropertyDefinition(self::class))
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
