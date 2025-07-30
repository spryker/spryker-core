<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business;

use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportResponseTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;

interface FileImportMerchantPortalGuiFacadeInterface
{
    /**
     * Specification:
     * - Creates merchant file import entity in the database.
     * - Returns `MerchantFileImportResponseTransfer.isSuccessful` when operation is successful
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportResponseTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportResponseTransfer;

    /**
     * Specification:
     * - Finds a merchant file import by provided criteria
     * - Returns `MerchantFileImportTransfer` if found
     * - Returns null if not found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer|null
     */
    public function findMerchantFileImport(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): ?MerchantFileImportTransfer;
}
