<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\PathMethodDataTransfer;
use Generated\Shared\Transfer\PathSchemaDataTransfer;

interface PathGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $requestSchemaDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $requestSchemaDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @return array
     */
    public function getPaths(): array;
}
