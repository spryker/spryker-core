<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Persistence;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;

interface DataImportMerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function saveDataImportMerchantFile(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): DataImportMerchantFileTransfer;
}
