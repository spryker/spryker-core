<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\SchemaPropertyComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object Property.
 *  - This component partly covers Schema Object Properties in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaPropertySpecificationComponent implements SchemaPropertySpecificationComponentInterface
{
    /**
     * @var string
     */
    protected const KEY_REF = '$ref';

    /**
     * @var string
     */
    protected const KEY_ITEMS = 'items';

    /**
     * @var string
     */
    protected const KEY_ONEOF = 'oneOf';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const KEY_NULLABLE = 'nullable';

    /**
     * @var string
     */
    protected const VALUE_TYPE_ARRAY = 'array';

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer): array
    {
        $property = [];

        $property = $this->addBasicPropertyData($schemaPropertyComponentTransfer, $property);
        $property = $this->addItemPropertyData($schemaPropertyComponentTransfer, $property);

        return [$schemaPropertyComponentTransfer->getName() => $property];
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param array<mixed> $schemaProperty
     *
     * @return array<mixed>
     */
    protected function addBasicPropertyData(SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer, array $schemaProperty): array
    {
        if ($schemaPropertyComponentTransfer->getType()) {
            $schemaProperty[SchemaPropertyComponentTransfer::TYPE] = $schemaPropertyComponentTransfer->getType();
        }
        if ($schemaPropertyComponentTransfer->getSchemaReference()) {
            $schemaProperty[static::KEY_REF] = $schemaPropertyComponentTransfer->getSchemaReference();
        }

        return $schemaProperty;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param array<mixed> $schemaProperty
     *
     * @return array<mixed>
     */
    protected function addItemPropertyData(SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer, array $schemaProperty): array
    {
        if ($schemaPropertyComponentTransfer->getItemsSchemaReference()) {
            $schemaProperty[SchemaPropertyComponentTransfer::TYPE] = static::VALUE_TYPE_ARRAY;
            $schemaProperty[static::KEY_ITEMS][static::KEY_REF] = $schemaPropertyComponentTransfer->getItemsSchemaReference();
        }
        if ($schemaPropertyComponentTransfer->getItemsType()) {
            $schemaProperty[static::KEY_ITEMS][static::KEY_TYPE] = $schemaPropertyComponentTransfer->getItemsType();
        }
        if ($schemaPropertyComponentTransfer->getType() === static::VALUE_TYPE_ARRAY && $schemaPropertyComponentTransfer->getOneOf()) {
            foreach ($schemaPropertyComponentTransfer->getOneOf() as $oneOfItem) {
                $schemaProperty[static::KEY_ONEOF][] = [static::KEY_REF => $oneOfItem];
            }
        }
        if ($schemaPropertyComponentTransfer->getType() === static::VALUE_TYPE_ARRAY && !$schemaPropertyComponentTransfer->getItemsType() && !$schemaPropertyComponentTransfer->getOneOf()) {
            $schemaProperty[static::KEY_ITEMS] = [];
        }
        if ($schemaPropertyComponentTransfer->getIsNullable()) {
            $schemaProperty[static::KEY_NULLABLE] = true;
        }

        return $schemaProperty;
    }
}
