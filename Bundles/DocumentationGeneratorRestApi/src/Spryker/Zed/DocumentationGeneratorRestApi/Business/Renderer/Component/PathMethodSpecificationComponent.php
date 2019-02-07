<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\PathMethodComponentTransfer;

/**
 * Specification:
 *  - This component describes a single API operation on a path.
 *  - This component covers Operation Object in OpenAPI specification format (see https://swagger.io/specification/#operationObject).
 */
class PathMethodSpecificationComponent implements PathMethodSpecificationComponentInterface
{
    protected const KEY_REQUEST_BODY = 'requestBody';

    /**
     * @var \Generated\Shared\Transfer\PathMethodComponentTransfer|null $pathMethodComponentTransfer
     */
    protected $pathMethodComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $pathMethodComponentTransfer
     *
     * @return void
     */
    public function setPathMethodComponentTransfer(PathMethodComponentTransfer $pathMethodComponentTransfer): void
    {
        $this->pathMethodComponentTransfer = $pathMethodComponentTransfer;
    }

    /**
     * @return array
     */
    public function getSpecificationComponentData(): array
    {
        if (!$this->validatePathMethodComponentTransfer()) {
            return [];
        }

        $pathData[PathMethodComponentTransfer::SUMMARY] = $this->pathMethodComponentTransfer->getSummary();
        $pathData[PathMethodComponentTransfer::TAGS] = $this->pathMethodComponentTransfer->getTags();
        if ($this->pathMethodComponentTransfer->getParameters()) {
            $pathData[PathMethodComponentTransfer::PARAMETERS] = $this->pathMethodComponentTransfer->getParameters();
        }
        if ($this->pathMethodComponentTransfer->getRequest()) {
            $pathData[static::KEY_REQUEST_BODY] = $this->pathMethodComponentTransfer->getRequest();
        }
        if ($this->pathMethodComponentTransfer->getSecurity()) {
            $pathData[PathMethodComponentTransfer::SECURITY] = $this->pathMethodComponentTransfer->getSecurity();
        }
        $pathData[PathMethodComponentTransfer::RESPONSES] = [];
        foreach ($this->pathMethodComponentTransfer->getResponses() as $response) {
            $pathData[PathMethodComponentTransfer::RESPONSES] += $response;
            ksort($pathData[PathMethodComponentTransfer::RESPONSES], SORT_NATURAL);
        }

        return [$this->pathMethodComponentTransfer->getMethod() => $pathData];
    }

    /**
     * @return bool
     */
    protected function validatePathMethodComponentTransfer(): bool
    {
        if (!$this->pathMethodComponentTransfer) {
            return false;
        }

        $this->pathMethodComponentTransfer->requireMethod();
        $this->pathMethodComponentTransfer->requireSummary();
        $this->pathMethodComponentTransfer->requireTags();
        $this->pathMethodComponentTransfer->getResponses();

        return true;
    }
}
