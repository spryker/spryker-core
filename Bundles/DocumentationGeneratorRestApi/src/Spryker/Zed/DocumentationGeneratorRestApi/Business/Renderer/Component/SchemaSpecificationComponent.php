<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object.
 *  - This component partly covers Schema Object in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaSpecificationComponent extends AbstractSpecificationComponent implements SchemaSpecificationComponentInterface
{
    protected const KEY_PROPERTIES = 'properties';
    protected const KEY_REQUIRED = 'required';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer $specificationComponentTransfer
     */
    protected $specificationComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer $schemaComponentTransfer
     *
     * @return void
     */
    public function setSchemaComponentTransfer(OpenApiSpecificationSchemaComponentTransfer $schemaComponentTransfer): void
    {
        $this->specificationComponentTransfer = $schemaComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        $schemaData[$this->specificationComponentTransfer->getName()][static::KEY_PROPERTIES] = $this->specificationComponentTransfer->getProperties();
        if ($this->specificationComponentTransfer->getRequired()) {
            $schemaData[$this->specificationComponentTransfer->getName()][static::KEY_REQUIRED] = $this->specificationComponentTransfer->getRequired();
        }

        return $schemaData;
    }

    /**
     * @return array
     */
    protected function getRequiredProperties(): array
    {
        return [
            $this->specificationComponentTransfer->getName(),
            $this->specificationComponentTransfer->getProperties(),
        ];
    }
}
