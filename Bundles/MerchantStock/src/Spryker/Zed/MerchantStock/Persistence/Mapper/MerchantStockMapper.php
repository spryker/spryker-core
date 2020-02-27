<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantStockMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $merchantStocksData
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantStocksDataToMerchantTransfer(
        ObjectCollection $merchantStocksData,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        $stockCollection = new ArrayObject();

        foreach ($merchantStocksData as $merchantStockEntity) {
            $stockCollection->append($this->createStockTransfer($merchantStockEntity));
        }

        return $merchantTransfer->setStockCollection($stockCollection);
    }

    /**
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function createStockTransfer(SpyMerchantStock $merchantStockEntity): StockTransfer
    {
        return (new StockTransfer())
            ->setIdStock($merchantStockEntity->getFkStock())
            ->setName($merchantStockEntity->getStockName());
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
