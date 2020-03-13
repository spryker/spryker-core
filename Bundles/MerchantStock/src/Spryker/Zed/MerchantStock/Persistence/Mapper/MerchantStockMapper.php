<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
        return $merchantStockTransfer->fromArray($merchantStockEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function mapMerchantTransferToMerchantStockEntity(
        MerchantTransfer $merchantTransfer,
        SpyMerchantStock $merchantStockEntity
    ): SpyMerchantStock {
        return $merchantStockEntity->setFkMerchant($merchantTransfer->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function mapStockTransferToMerchantStockEntity(
        StockTransfer $stockTransfer,
        SpyMerchantStock $merchantStockEntity
    ): SpyMerchantStock {
        return $merchantStockEntity->setFkStock($stockTransfer->getIdStock());
    }
}
