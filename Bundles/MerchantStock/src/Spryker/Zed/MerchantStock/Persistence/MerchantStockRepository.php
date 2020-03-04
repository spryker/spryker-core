<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
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
        $merchantStocksData = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->leftJoinWithSpyStock()
            ->filterByFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->find();

        $stockCollectionTransfer = new StockCollectionTransfer();

        foreach ($merchantStocksData as $merchantStockEntity) {
            $stockCollectionTransfer->addStock(
                $this->getFactory()
                    ->createMerchantStockMapper()
                    ->mapStockDataToStockTransfer($merchantStockEntity->getSpyStock(), new StockTransfer())
            );
        }

        return $stockCollectionTransfer;
    }
}
