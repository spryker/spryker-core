<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface ResourceTransferAnalyzerInterface
{
    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    public function isTransferValid(string $transferClassName): bool;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array
     */
    public function getTransferMetadata(AbstractTransfer $transfer): array;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestDataSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestAttributesSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceDataSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionDataSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseAttributesSchemaNameFromTransferClassName(string $transferClassName): string;

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResourceRelationshipSchemaNameFromTransferClassName(string $transferClassName): string;
}
