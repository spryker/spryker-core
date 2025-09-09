<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;

interface DataImportMerchantFileReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer;

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer|null
     */
    public function findDataImportMerchantFileByUuid(string $uuid): ?DataImportMerchantFileTransfer;

    /**
     * @return array<string, array<int|string, string>>
     */
    public function getFilterOptions(): array;
}
