<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOfferStockBuilder;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOfferStockHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function haveProductOfferStock(array $seedData = []): ProductOfferStockTransfer
    {
        $productOfferStockTransfer = (new ProductOfferStockBuilder($seedData))->build();
        $productOfferStockTransfer->setIdProductOfferStock(null);

        $productOfferStockEntity = new SpyProductOfferStock();
        $productOfferStockEntity->fromArray($productOfferStockTransfer->toArray());
        $productOfferStockEntity->save();

        $productOfferStockTransfer->setIdProductOfferStock($productOfferStockEntity->getIdProductOfferStock());

        return $productOfferStockTransfer;
    }
}
