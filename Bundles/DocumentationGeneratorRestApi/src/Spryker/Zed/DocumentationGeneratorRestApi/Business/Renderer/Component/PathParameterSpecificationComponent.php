<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\PathParameterComponentTransfer;

/**
 * Specification:
 *  - This component describes a single operation parameter.
 *  - It covers Parameter Object in OpenAPI specification format (see https://swagger.io/specification/#parameterObject)
 */
class PathParameterSpecificationComponent implements PathParameterSpecificationComponentInterface
{
    protected const KEY_SCHEMA = 'schema';
    protected const KEY_TYPE = 'type';

    /**
     * @var \Generated\Shared\Transfer\PathParameterComponentTransfer|null $pathParameterComponentTransfer
     */
    protected $pathParameterComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PathParameterComponentTransfer $pathParameterComponentTransfer
     *
     * @return void
     */
    public function setPathParameterComponentTransfer(PathParameterComponentTransfer $pathParameterComponentTransfer): void
    {
        $this->pathParameterComponentTransfer = $pathParameterComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        $result = [];
        if (!$this->validatePathParameterComponentTransfer()) {
            return $result;
        }

        $result[PathParameterComponentTransfer::NAME] = $this->pathParameterComponentTransfer->getName();
        $result[PathParameterComponentTransfer::IN] = $this->pathParameterComponentTransfer->getIn();
        $result[PathParameterComponentTransfer::REQUIRED] = $this->pathParameterComponentTransfer->getRequired();
        if ($this->pathParameterComponentTransfer->getDescription()) {
            $result[PathParameterComponentTransfer::DESCRIPTION] = $this->pathParameterComponentTransfer->getDescription();
        }
        if ($this->pathParameterComponentTransfer->getDeprecated() !== null) {
            $result[PathParameterComponentTransfer::DEPRECATED] = $this->pathParameterComponentTransfer->getDeprecated();
        }
        if ($this->pathParameterComponentTransfer->getAllowEmptyValue() !== null) {
            $result[PathParameterComponentTransfer::ALLOW_EMPTY_VALUE] = $this->pathParameterComponentTransfer->getAllowEmptyValue();
        }

        $result[static::KEY_SCHEMA] = [
            static::KEY_TYPE => $this->pathParameterComponentTransfer->getSchemaType(),
        ];

        return $result;
    }

    /**
     * @return bool
     */
    protected function validatePathParameterComponentTransfer(): bool
    {
        if (!$this->pathParameterComponentTransfer) {
            return false;
        }

        $this->pathParameterComponentTransfer->requireName();
        $this->pathParameterComponentTransfer->requireIn();
        $this->pathParameterComponentTransfer->requireRequired();
        $this->pathParameterComponentTransfer->getSchemaType();

        return true;
    }
}
