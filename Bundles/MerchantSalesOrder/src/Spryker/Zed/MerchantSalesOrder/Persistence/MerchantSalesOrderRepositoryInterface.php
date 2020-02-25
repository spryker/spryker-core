<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantSalesOrderRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderCollection(
        MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
    ): MerchantOrderCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(
        MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
    ): ?MerchantOrderTransfer;
}
