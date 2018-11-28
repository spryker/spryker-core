<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\PathResponseComponentTransfer;

/**
 * Specification:
 *  - This component describes a single response from an API Operation.
 *  - This component covers Operation Object in OpenAPI specification format (see https://swagger.io/specification/#operationObject).
 */
class PathResponseSpecificationComponent implements PathResponseSpecificationComponentInterface
{
    protected const KEY_APPLICATION_JSON = 'application/json';
    protected const KEY_CONTENT = 'content';
    protected const KEY_REF = '$ref';
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var \Generated\Shared\Transfer\PathResponseComponentTransfer|null $pathResponseComponentTransfer
     */
    protected $pathResponseComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PathResponseComponentTransfer $pathResponseComponentTransfer
     *
     * @return void
     */
    public function setPathResponseComponentTransfer(PathResponseComponentTransfer $pathResponseComponentTransfer): void
    {
        $this->pathResponseComponentTransfer = $pathResponseComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validatePathResponseComponentTransfer()) {
            return [];
        }

        $result[PathResponseComponentTransfer::DESCRIPTION] = $this->pathResponseComponentTransfer->getDescription();
        if ($this->pathResponseComponentTransfer->getJsonSchemaRef()) {
            $result[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $this->pathResponseComponentTransfer->getJsonSchemaRef();
        }

        return [$this->pathResponseComponentTransfer->getCode() => $result];
    }

    /**
     * @return bool
     */
    protected function validatePathResponseComponentTransfer(): bool
    {
        if (!$this->pathResponseComponentTransfer) {
            return false;
        }

        $this->pathResponseComponentTransfer->requireCode();
        $this->pathResponseComponentTransfer->requireDescription();

        return true;
    }
}
