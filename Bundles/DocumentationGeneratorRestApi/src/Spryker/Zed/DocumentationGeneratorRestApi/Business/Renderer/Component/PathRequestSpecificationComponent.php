<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\PathRequestComponentTransfer;

/**
 * Specification:
 *  - This component describes a single request body.
 *  - It covers Request Body Object in OpenAPI specification format (see https://swagger.io/specification/#requestBodyObject)
 */
class PathRequestSpecificationComponent implements PathRequestSpecificationComponentInterface
{
    protected const KEY_APPLICATION_JSON = 'application/json';
    protected const KEY_CONTENT = 'content';
    protected const KEY_REF = '$ref';
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var \Generated\Shared\Transfer\PathRequestComponentTransfer|null $pathRequestComponentTransfer
     */
    protected $pathRequestComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PathRequestComponentTransfer $pathRequestComponentTransfer
     *
     * @return void
     */
    public function setPathRequestComponentTransfer(PathRequestComponentTransfer $pathRequestComponentTransfer): void
    {
        $this->pathRequestComponentTransfer = $pathRequestComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        $result = [];
        if (!$this->validatePathRequestComponentTransfer()) {
            return $result;
        }

        $result[PathRequestComponentTransfer::DESCRIPTION] = $this->pathRequestComponentTransfer->getDescription();
        $result[PathRequestComponentTransfer::REQUIRED] = $this->pathRequestComponentTransfer->getRequired();
        if ($this->pathRequestComponentTransfer->getJsonSchemaRef()) {
            $result[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $this->pathRequestComponentTransfer->getJsonSchemaRef();
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function validatePathRequestComponentTransfer(): bool
    {
        if (!$this->pathRequestComponentTransfer) {
            return false;
        }

        $this->pathRequestComponentTransfer->requireDescription();
        $this->pathRequestComponentTransfer->requireRequired();
        $this->pathRequestComponentTransfer->requireJsonSchemaRef();

        return true;
    }
}
