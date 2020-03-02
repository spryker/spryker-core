<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockRepository extends AbstractRepository implements MerchantStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollectionByMerchant(MerchantTransfer $merchantTransfer): StockCollectionTransfer
    {
        $stocksData = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->filterByFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->useSpyStockQuery()
                ->withColumn(SpyStockTableMap::COL_ID_STOCK, StockTransfer::ID_STOCK)
                ->withColumn(SpyStockTableMap::COL_NAME, StockTransfer::NAME)
            ->endUse()
            ->find()
            ->toArray();

        $stockCollectionTransfer = new StockCollectionTransfer();

        foreach ($stocksData as $stock) {
            $stockCollectionTransfer->addStock(
                $this->getFactory()->createMerchantStockMapper()->mapStockDataToStockTransfer($stock, new StockTransfer())
            );
        }

        return $stockCollectionTransfer;
    }
}
