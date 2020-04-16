<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\SchemaItemsComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object Property.
 *  - This component partly covers Schema Object Properties in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaItemsSpecificationComponent implements SchemaItemsSpecificationComponentInterface
{
    protected const KEY_REF = '$ref';
    protected const KEY_ITEMS = 'items';
    protected const KEY_ONEOF = 'oneOf';
    protected const KEY_TYPE = 'type';
    protected const KEY_NULLABLE = 'nullable';
    protected const VALUE_TYPE_ARRAY = 'array';

    /**
     * @var \Generated\Shared\Transfer\SchemaItemsComponentTransfer|null $schemaPropertyComponentTransfer
     */
    protected $schemaItemsComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchemaItemsComponentTransfer $schemaItemsComponentTransfer
     *
     * @return void
     */
    public function setSchemaItemsComponentTransfer(SchemaItemsComponentTransfer $schemaItemsComponentTransfer): void
    {
        $this->schemaItemsComponentTransfer = $schemaItemsComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validateSchemaPropertyComponentTransfer()) {
            return [];
        }

        return $this->addOneOf();
    }

    /**
     * @return array
     */
    protected function addOneOf(): array
    {
        $schemaItems = [];

        if ($this->schemaItemsComponentTransfer->getOneOf()) {
            foreach ($this->schemaItemsComponentTransfer->getOneOf() as $ref) {
                $schemaItems[static::KEY_ONEOF][][static::KEY_REF] = $ref;
            }
        }

        return $schemaItems;
    }

    /**
     * @return bool
     */
    protected function validateSchemaPropertyComponentTransfer(): bool
    {
        if (!$this->schemaItemsComponentTransfer) {
            return false;
        }

        $this->schemaItemsComponentTransfer->requireOneOf();

        return true;
    }
}
