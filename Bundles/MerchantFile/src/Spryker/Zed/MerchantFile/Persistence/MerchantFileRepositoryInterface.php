<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

interface MerchantFileRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer;
}
