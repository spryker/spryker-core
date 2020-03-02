<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantStockHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function haveMerchantStock(MerchantTransfer $merchantTransfer, StockTransfer $stockTransfer): SpyMerchantStock
    {
        $merchantStockEntity = (new SpyMerchantStock())
            ->setFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->setFkStock($stockTransfer->requireIdStock()->getIdStock());

        $merchantStockEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantStockEntity): void {
            $this->cleanupMerchantStock($merchantStockEntity);
        });

        return $merchantStockEntity;
    }

    /**
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return void
     */
    protected function cleanupMerchantStock(SpyMerchantStock $merchantStockEntity): void
    {
        $this->debug(sprintf('Deleting Merchant Stock: %d', $merchantStockEntity->getIdMerchantStock()));
        SpyMerchantStockQuery::create()
            ->findByIdMerchantStock($merchantStockEntity->getIdMerchantStock())
            ->delete();
    }
}
