<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;

interface RestApiDocumentationPathGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void;

    /**
     * @return array
     */
    public function getPaths(): array;
}
