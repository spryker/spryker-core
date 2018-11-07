<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationPathParameterComponentTransfer;

/**
 * Specification:
 *  - This component describes a single operation parameter.
 *  - It covers Parameter Object in OpenAPI specification format (see https://swagger.io/specification/#parameterObject)
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PathParameterSpecificationComponent implements PathParameterSpecificationComponentInterface
{
    protected const KEY_DESCRIPTION = 'description';
    protected const KEY_IN = 'in';
    protected const KEY_NAME = 'name';
    protected const KEY_REQUIRED = 'required';
    protected const KEY_SCHEMA = 'schema';
    protected const KEY_TYPE = 'type';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationPathParameterComponentTransfer $pathParameterComponentTransfer
     */
    protected $pathParameterComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathParameterComponentTransfer $pathParameterComponentTransfer
     *
     * @return void
     */
    public function setPathParameterComponentTransfer(OpenApiSpecificationPathParameterComponentTransfer $pathParameterComponentTransfer): void
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

        $result[static::KEY_NAME] = $this->pathParameterComponentTransfer->getName();
        $result[static::KEY_IN] = $this->pathParameterComponentTransfer->getIn();
        $result[static::KEY_REQUIRED] = $this->pathParameterComponentTransfer->getRequired();
        if ($this->pathParameterComponentTransfer->getDescription()) {
            $result[static::KEY_DESCRIPTION] = $this->pathParameterComponentTransfer->getDescription();
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
        if ($this->pathParameterComponentTransfer === null) {
            return false;
        }

        $this->pathParameterComponentTransfer->requireName();
        $this->pathParameterComponentTransfer->requireIn();
        $this->pathParameterComponentTransfer->requireRequired();
        $this->pathParameterComponentTransfer->getSchemaType();

        return true;
    }
}
