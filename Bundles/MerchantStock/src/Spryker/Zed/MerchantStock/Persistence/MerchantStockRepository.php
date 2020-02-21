<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockRepository extends AbstractRepository implements MerchantStockRepositoryInterface
{
    protected const STOCK_NAME = 'stockName';

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantStocksByMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantStocksData = $this->getFactory()
            ->createMerchantStockQuery()
                ->useSpyStockQuery()
                    ->withColumn(SpyStockTableMap::COL_NAME, static::STOCK_NAME)
                ->endUse()
            ->findByFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant());

        return $this->getFactory()
            ->createMerchantStockMapper()
            ->mapMerchantStocksDataToMerchantTransfer($merchantStocksData, $merchantTransfer);
    }
}
