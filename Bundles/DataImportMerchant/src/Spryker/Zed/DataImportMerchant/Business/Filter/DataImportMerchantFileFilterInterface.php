<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;

interface DataImportMerchantFileFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer>>
     */
    public function filterDataImportMerchantFilesByValidity(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $validDataImportMerchantFileTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $invalidDataImportMerchantFileTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer>
     */
    public function mergeDataImportMerchantFiles(
        ArrayObject $validDataImportMerchantFileTransfers,
        ArrayObject $invalidDataImportMerchantFileTransfers
    ): ArrayObject;
}
