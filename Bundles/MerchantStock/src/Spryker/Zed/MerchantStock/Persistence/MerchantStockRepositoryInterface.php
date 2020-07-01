<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;

interface MerchantStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function get(MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer): StockCollectionTransfer;

    /**
     * @param int $idMerchant
     *
     * @throws \Spryker\Zed\MerchantStock\Persistence\Exception\DefaultMerchantStockNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function getDefaultMerchantStock(int $idMerchant): StockTransfer;
}
