<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantStockBuilder;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantStockHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function haveMerchantStock(array $seedData): MerchantStockTransfer
    {
        $merchantStockTransfer = (new MerchantStockBuilder($seedData))->build();

        $merchantStockEntity = new SpyMerchantStock();
        $merchantStockEntity->fromArray($merchantStockTransfer->toArray());
        $merchantStockEntity->save();

        $merchantStockTransfer->fromArray($merchantStockEntity->toArray());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantStockEntity): void {
            $this->cleanupMerchantStock($merchantStockEntity);
        });

        return $merchantStockTransfer;
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
