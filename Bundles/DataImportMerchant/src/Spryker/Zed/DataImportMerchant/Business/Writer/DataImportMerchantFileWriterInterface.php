<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Business\Writer;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;

interface DataImportMerchantFileWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function writeFileToFileSystem(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): DataImportMerchantFileTransfer;
}
