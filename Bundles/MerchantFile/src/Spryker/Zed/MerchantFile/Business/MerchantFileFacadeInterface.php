<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

interface MerchantFileFacadeInterface
{
    /**
     * Specification:
     * - Uploads a file for a merchant.
     * - Validates file before upload
     * - Persists file metadata into the database.
     * - Executes `MerchantFileValidationPluginInterface` plugin stack.
     * - Executes `MerchantFilePostSavePluginInterface` plugin stack.
     * - Returns `MerchantFileResultTransfer`
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer;

    /**
     * Specification:
     * - Retrieves a single merchant file based on the provided criteria.
     * - Returns `MerchantFileTransfer` if found, otherwise returns `null`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer;

    /**
     * Specification:
     * - Retrieves a collection of merchant files based on the provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer;

    /**
     * Specification:
     * - Reads a merchant file based on the provided criteria.
     * - Throws `MerchantFileNotFoundException` if the file is not found.
     * - Returns a resource stream for the file content.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer);
}
