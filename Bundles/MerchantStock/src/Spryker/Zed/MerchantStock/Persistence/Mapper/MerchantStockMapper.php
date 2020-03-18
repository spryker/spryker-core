<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\Stock\Persistence\SpyStock;

class MerchantStockMapper
{
    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function mapStockEntityToStockTransfer(
        SpyStock $stockEntity,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer->fromArray($stockEntity->toArray());
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
        return $merchantStockTransfer->setIdMerchantStock($merchantStockEntity->getIdMerchantStock())
            ->setIdMerchant($merchantStockEntity->getFkMerchant())
            ->setIdStock($merchantStockEntity->getFkStock())
            ->setIsDefault($merchantStockEntity->getIsDefault());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function mapMerchantStockTransferToMerchantStockEntity(
        MerchantStockTransfer $merchantStockTransfer,
        SpyMerchantStock $merchantStockEntity
    ): SpyMerchantStock {
        return $merchantStockEntity->setIdMerchantStock($merchantStockTransfer->getIdMerchantStock())
            ->setFkMerchant($merchantStockTransfer->getIdMerchant())
            ->setFkStock($merchantStockTransfer->getIdStock())
            ->setIsDefault($merchantStockTransfer->getIsDefault());
    }
}
