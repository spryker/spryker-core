<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business\Saver;

use Generated\Shared\Transfer\MerchantFileImportResponseTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;

interface MerchantFileImportSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportResponseTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportResponseTransfer;
}
