<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface FileImportMerchantPortalGuiEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer;
}
