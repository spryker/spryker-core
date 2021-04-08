<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\StockAddress\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StockAddressBuilder;
use Generated\Shared\Transfer\StockAddressTransfer;
use Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class StockAddressDataHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    public function haveStockAddress(array $seedData = []): StockAddressTransfer
    {
        $stockAddressTransfer = (new StockAddressBuilder($seedData))->build();
        $stockAddressTransfer = $this->createStockAddress($stockAddressTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($stockAddressTransfer) {
            $this->cleanupStockAddress($stockAddressTransfer->getIdStockOrFail());
        });

        return $stockAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressTransfer
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    protected function createStockAddress(StockAddressTransfer $stockAddressTransfer): StockAddressTransfer
    {
        $stockAddressEntity = SpyStockAddressQuery::create()
            ->filterByFkStock($stockAddressTransfer->getIdStockOrFail())
            ->findOneOrCreate();

        $stockAddressEntity->fromArray($stockAddressTransfer->toArray());
        $stockAddressEntity->setFkCountry($stockAddressTransfer->getCountryOrFail()->getIdCountryOrFail());
        if ($stockAddressTransfer->getRegion() && $stockAddressTransfer->getRegion()->getIdRegion()) {
            $stockAddressEntity->setFkRegion($stockAddressTransfer->getRegion()->getIdRegion());
        }

        $stockAddressEntity->save();

        return $stockAddressTransfer->fromArray($stockAddressEntity->toArray(), true);
    }

    /**
     * @param int $idStockAddress
     *
     * @return void
     */
    protected function cleanupStockAddress(int $idStockAddress): void
    {
        SpyStockAddressQuery::create()
            ->filterByIdStockAddress($idStockAddress)
            ->delete();
    }
}
