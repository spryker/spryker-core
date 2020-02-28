<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\MerchantStock\Persistence\Map\SpyMerchantStockTableMap;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;

class MerchantStockMapper
{
    /**
     * @param array $stock
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function mapStockDataToStockTransfer(
        array $stock,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer
            ->setIdStock($stock[SpyMerchantStockTableMap::COL_FK_STOCK])
            ->setName($stock[SpyStockTableMap::COL_NAME]);
    }

    /**
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function mapMerchantStockEntityToMerchantStockTransfer(
        SpyMerchantStock $merchantStockEntity,
        MerchantStockTransfer $merchantStockTransfer
    ): MerchantStockTransfer {
        return $merchantStockTransfer->fromArray($merchantStockEntity->toArray(), true);
    }
}
