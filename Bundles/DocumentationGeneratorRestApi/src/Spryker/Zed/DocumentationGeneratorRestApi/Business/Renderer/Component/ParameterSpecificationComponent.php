<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\ParameterComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Object.
 *  - This component covers Parameter Object in OpenAPI specification format (see https://swagger.io/specification/#componentsObject).
 */
class ParameterSpecificationComponent implements ParameterSpecificationComponentInterface
{
    /**
     * @var \Generated\Shared\Transfer\ParameterComponentTransfer|null $parameterComponentTransfer
     */
    protected $parameterComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ParameterComponentTransfer $parameterComponentTransfer
     *
     * @return void
     */
    public function setParameterComponentTransfer(ParameterComponentTransfer $parameterComponentTransfer): void
    {
        $this->parameterComponentTransfer = $parameterComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validateComponentTransfer()) {
            return [];
        }

        $propertyData = [];
        $propertyData[$this->parameterComponentTransfer->getRefName()][ParameterComponentTransfer::NAME] = $this->parameterComponentTransfer->getName();
        $propertyData[$this->parameterComponentTransfer->getRefName()][ParameterComponentTransfer::IN] = $this->parameterComponentTransfer->getIn();
        $propertyData[$this->parameterComponentTransfer->getRefName()][ParameterComponentTransfer::DESCRIPTION] = $this->parameterComponentTransfer->getDescription();
        $propertyData[$this->parameterComponentTransfer->getRefName()][ParameterComponentTransfer::SCHEMA] = $this->parameterComponentTransfer->getSchema()->toArray();
        $propertyData[$this->parameterComponentTransfer->getRefName()][ParameterComponentTransfer::REQUIRED] = $this->parameterComponentTransfer->getRequired();

        return $propertyData;
    }

    /**
     * @return bool
     */
    protected function validateComponentTransfer(): bool
    {
        if (!$this->parameterComponentTransfer) {
            return false;
        }

        $this->parameterComponentTransfer->requireName();
        $this->parameterComponentTransfer->requireIn();
        $this->parameterComponentTransfer->requireSchema();

        return true;
    }
}
