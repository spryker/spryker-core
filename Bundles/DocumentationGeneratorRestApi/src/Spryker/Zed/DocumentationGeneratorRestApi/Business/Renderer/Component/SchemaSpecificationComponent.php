<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\SchemaComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object.
 *  - This component partly covers Schema Object in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaSpecificationComponent implements SchemaSpecificationComponentInterface
{
    /**
     * @var \Generated\Shared\Transfer\SchemaComponentTransfer|null $schemaComponentTransfer
     */
    protected $schemaComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     *
     * @return void
     */
    public function setSchemaComponentTransfer(SchemaComponentTransfer $schemaComponentTransfer): void
    {
        $this->schemaComponentTransfer = $schemaComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validateSchemaComponentTransfer()) {
            return [];
        }

        if (count($this->schemaComponentTransfer->getProperties()) === 0) {
            //empty object is needed for generation of valid OpenAPI scheme
            return [
                $this->schemaComponentTransfer->getName() => (object)[],
            ];
        }

        $schemaData[$this->schemaComponentTransfer->getName()][SchemaComponentTransfer::PROPERTIES] = array_merge(...$this->schemaComponentTransfer->getProperties());
        if ($this->schemaComponentTransfer->getRequired()) {
            $schemaData[$this->schemaComponentTransfer->getName()][SchemaComponentTransfer::REQUIRED] = $this->schemaComponentTransfer->getRequired();
        }

        return $schemaData;
    }

    /**
     * @return bool
     */
    protected function validateSchemaComponentTransfer(): bool
    {
        if (!$this->schemaComponentTransfer) {
            return false;
        }

        $this->schemaComponentTransfer->requireName();
        $this->schemaComponentTransfer->requireProperties();

        return true;
    }
}
