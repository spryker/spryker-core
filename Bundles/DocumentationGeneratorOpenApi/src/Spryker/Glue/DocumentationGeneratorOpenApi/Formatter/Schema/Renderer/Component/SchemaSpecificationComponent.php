<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\SchemaComponentTransfer;
use stdClass;

/**
 * Specification:
 *  - This component describes a single Schema Object.
 *  - This component partly covers Schema Object in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaSpecificationComponent implements SchemaSpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(SchemaComponentTransfer $schemaComponentTransfer): array
    {
        if (count($schemaComponentTransfer->getProperties()) === 0 && !$schemaComponentTransfer->getItems()) {
            return [
                $schemaComponentTransfer->getName() => new stdClass(),
            ];
        }

        $schemaData = [];
        $schemaData = $this->addProperties($schemaComponentTransfer, $schemaData);
        $schemaData = $this->addItems($schemaComponentTransfer, $schemaData);
        $schemaData = $this->addRequired($schemaComponentTransfer, $schemaData);
        $schemaData = $this->addType($schemaComponentTransfer, $schemaData);

        return $schemaData;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     * @param array<mixed> $schemaData
     *
     * @return array<mixed>
     */
    protected function addProperties(SchemaComponentTransfer $schemaComponentTransfer, array $schemaData): array
    {
        if (count($schemaComponentTransfer->getProperties())) {
            $schemaData[$schemaComponentTransfer->getName()][SchemaComponentTransfer::PROPERTIES] = array_merge(...$schemaComponentTransfer->getProperties());
        }

        return $schemaData;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     * @param array<mixed> $schemaData
     *
     * @return array<mixed>
     */
    protected function addItems(SchemaComponentTransfer $schemaComponentTransfer, array $schemaData): array
    {
        if ($schemaComponentTransfer->getItems()) {
            $schemaData[$schemaComponentTransfer->getName()][SchemaComponentTransfer::ITEMS] = $schemaComponentTransfer->getItems();
        }

        return $schemaData;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     * @param array<mixed> $schemaData
     *
     * @return array<mixed>
     */
    protected function addRequired(SchemaComponentTransfer $schemaComponentTransfer, array $schemaData): array
    {
        if ($schemaComponentTransfer->getRequired()) {
            $schemaData[$schemaComponentTransfer->getName()][SchemaComponentTransfer::REQUIRED] = $schemaComponentTransfer->getRequired();
        }

        return $schemaData;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     * @param array<mixed> $schemaData
     *
     * @return array<mixed>
     */
    protected function addType(SchemaComponentTransfer $schemaComponentTransfer, array $schemaData): array
    {
        if ($schemaComponentTransfer->getType()) {
            $schemaData[$schemaComponentTransfer->getName()][SchemaComponentTransfer::TYPE] = $schemaComponentTransfer->getType();
        }

        return $schemaData;
    }
}
