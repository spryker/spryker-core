<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer;

class PathMethodSpecificationComponent implements PathMethodSpecificationComponentInterface
{
    protected const KEY_PARAMETERS = 'parameters';
    protected const KEY_REQUEST_BODY = 'requestBody';
    protected const KEY_RESPONSES = 'responses';
    protected const KEY_SECURITY = 'security';
    protected const KEY_SUMMARY = 'summary';
    protected const KEY_TAGS = 'tags';

    /**
     * @var \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $pathMethodComponentTransfer
     */
    protected $pathMethodComponentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $pathMethodComponentTransfer
     *
     * @return void
     */
    public function setPathMethodComponentTransfer(OpenApiSpecificationPathMethodComponentTransfer $pathMethodComponentTransfer): void
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

        $pathData[static::KEY_SUMMARY] = $this->pathMethodComponentTransfer->getSummary();
        $pathData[static::KEY_TAGS] = $this->pathMethodComponentTransfer->getTags();
        if ($this->pathMethodComponentTransfer->getParameters()) {
            $pathData[static::KEY_PARAMETERS] = $this->pathMethodComponentTransfer->getParameters();
        }
        if ($this->pathMethodComponentTransfer->getRequest()) {
            $pathData[static::KEY_REQUEST_BODY] = $this->pathMethodComponentTransfer->getRequest();
        }
        if ($this->pathMethodComponentTransfer->getSecurity()) {
            $pathData[static::KEY_SECURITY] = $this->pathMethodComponentTransfer->getSecurity();
        }
        $pathData[static::KEY_RESPONSES] = [];
        foreach ($this->pathMethodComponentTransfer->getResponses() as $response) {
            $pathData[static::KEY_RESPONSES] += $response;
            ksort($pathData[static::KEY_RESPONSES], SORT_NATURAL);
        }

        return [$this->pathMethodComponentTransfer->getMethod() => $pathData];
    }

    /**
     * @return bool
     */
    protected function validatePathMethodComponentTransfer(): bool
    {
        if ($this->pathMethodComponentTransfer === null) {
            return false;
        }

        $this->pathMethodComponentTransfer->requireMethod();
        $this->pathMethodComponentTransfer->requireSummary();
        $this->pathMethodComponentTransfer->requireTags();
        $this->pathMethodComponentTransfer->getResponses();

        return true;
    }
}
