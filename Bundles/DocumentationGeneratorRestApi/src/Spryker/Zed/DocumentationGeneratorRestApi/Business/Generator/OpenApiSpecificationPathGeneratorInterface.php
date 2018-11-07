<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer;

interface OpenApiSpecificationPathGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @return array
     */
    public function getPaths(): array;
}
