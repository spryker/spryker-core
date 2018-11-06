<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object Property.
 *  - This component partly covers Schema Object Properties in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaPropertySpecificationComponent extends AbstractSpecificationComponent implements SchemaPropertySpecificationComponentInterface
{
    protected const KEY_TYPE = 'type';
    protected const KEY_REF = '$ref';
    protected const KEY_ITEMS = 'items';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     */
    protected $schemaPropertyComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     *
     * @return void
     */
    public function setSchemaPropertyComponentTransfer(OpenApiSpecificationSchemaPropertyComponentTransfer $schemaPropertyComponentTransfer): void
    {
        $this->schemaPropertyComponentTransfer = $schemaPropertyComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        $property = [];
        if ($this->schemaPropertyComponentTransfer->getType()) {
            $property[static::KEY_TYPE] = $this->schemaPropertyComponentTransfer->getType();
        }
        if ($this->schemaPropertyComponentTransfer->getSchemaReference()) {
            $property[static::KEY_REF] = $this->schemaPropertyComponentTransfer->getSchemaReference();
        }
        if ($this->schemaPropertyComponentTransfer->getItemsSchemaReference()) {
            $property[static::KEY_ITEMS][static::KEY_REF] = $this->schemaPropertyComponentTransfer->getItemsSchemaReference();
        }

        return [$this->schemaPropertyComponentTransfer->getName() => $property];
    }

    /**
     * @return array
     */
    protected function getRequiredProperties(): array
    {
        return [
            $this->schemaPropertyComponentTransfer->getName(),
        ];
    }
}
