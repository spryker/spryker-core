<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Collection\CollectionInterface;
use Spryker\Glue\Testify\OpenApi3\Object\ObjectInterface;
use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Primitive\PrimitiveInterface;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValue;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValues;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolverInterface;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferenceValue;

class Mapper implements MapperInterface
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolverInterface
     */
    protected $referenceResolver;

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolverInterface $referenceResolver
     */
    public function __construct(ReferenceResolverInterface $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }

    /**
     * @inheritdoc
     */
    public function mapObjectFromPayload(ObjectInterface $object, $payload): SchemaFieldInterface
    {
        $payload = (object)$payload;
        $values = new PropertyValues();
        $specification = $object->getObjectSpecification();

        foreach ($specification as $propertyName => $definition) {
            if (property_exists($payload, $propertyName)) {
                $values->setValue(
                    $propertyName,
                    $this->createPropertyValue(
                        $definition,
                        $payload->{$propertyName} ?? null
                    )
                );
            }
        }

        return $object->hydrate($values);
    }

    /**
     * @inheritdoc
     */
    public function mapCollectionFromPayload(CollectionInterface $collection, $payload): SchemaFieldInterface
    {
        $values = [];
        $definition = $collection->getElementDefinition();

        foreach ((array)$payload as $key => $element) {
            $values[$key] = $this->createPropertyValue($definition, $element);
        }

        return $collection->hydrate($values);
    }

    /**
     * @inheritdoc
     */
    public function mapPrimitiveFromPayload(PrimitiveInterface $primitive, $payload): SchemaFieldInterface
    {
        return $primitive->hydrate($payload);
    }

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition $definition
     * @param mixed $payload
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface
     */
    protected function createPropertyValue(
        PropertyDefinition $definition,
        $payload
    ): PropertyValueInterface {

        $class = $definition->getType();
        $instance = new $class();

        if (property_exists((object)$payload, '$ref')) {
            if ($instance instanceof ReferableInterface) {
                $payload = (object)$payload;

                return new ReferenceValue($definition, $payload->{'$ref'}, $this->referenceResolver);
            }

            trigger_error(sprintf(
                'Class %s should implement %s interface to be processed by `$ref`',
                $class,
                ReferableInterface::class
            ), E_USER_WARNING);
        }

        if ($instance instanceof ObjectInterface) {
            return new PropertyValue(
                $definition,
                $this->mapObjectFromPayload($instance, $payload)
            );
        }

        if ($instance instanceof CollectionInterface) {
            return new PropertyValue(
                $definition,
                $this->mapCollectionFromPayload($instance, $payload)
            );
        }

        if ($instance instanceof PrimitiveInterface) {
            return new PropertyValue(
                $definition,
                $this->mapPrimitiveFromPayload($instance, $payload)
            );
        }

        trigger_error(sprintf(
            'Class %s must implement one of the following interfaces: %s',
            $class,
            implode(',', [
                ObjectInterface::class,
                CollectionInterface::class,
                PrimitiveInterface::class,
            ])
        ), E_USER_WARNING);

        return new PropertyValue(
            $definition,
            $this->mapPrimitiveFromPayload(new Any(), $payload)
        );
    }
}
