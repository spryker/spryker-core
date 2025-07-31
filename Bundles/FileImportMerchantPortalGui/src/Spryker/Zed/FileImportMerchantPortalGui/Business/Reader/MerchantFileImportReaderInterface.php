<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business\Reader;

use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;

interface MerchantFileImportReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer|null
     */
    public function findMerchantFileImport(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): ?MerchantFileImportTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportCollection(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): MerchantFileImportCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportTableData(
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): MerchantFileImportCollectionTransfer;
}
