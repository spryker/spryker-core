<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductOfferStock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOfferStockBuilder;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use SprykerTest\Shared\Stock\Helper\StockDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper;

class ProductOfferStockDataHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seed
     * @param array<list<mixed>>|null $stockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function haveProductOfferStock(array $seed = [], ?array $stockTransfers = null): ProductOfferStockTransfer
    {
        $productOfferStockBuilder = new ProductOfferStockBuilder($seed);
        $productOfferStockTransfer = $productOfferStockBuilder->build();

        $productOfferStockTransfer = $this->setProductOfferStockDependencies(
            $seed,
            $productOfferStockTransfer,
            $stockTransfers,
        );

        $productOfferStockEntity = (new SpyProductOfferStock());
        $productOfferStockEntity->fromArray($productOfferStockTransfer->toArray());
        $productOfferStockEntity->setFkStock($productOfferStockTransfer->getStock()->getIdStock());
        $productOfferStockEntity->setFkProductOffer($productOfferStockTransfer->getIdProductOffer());
        $productOfferStockEntity->save();

        $productOfferStockTransfer->fromArray($productOfferStockEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productOfferStockEntity): void {
            $productOfferStockEntity->delete();
        });

        return $productOfferStockTransfer;
    }

    /**
     * @param array $seed
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param array<list<mixed>>|null $stockTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    protected function setProductOfferStockDependencies(
        array $seed,
        ProductOfferStockTransfer $productOfferStockTransfer,
        ?array $stockTransfers = null
    ): ProductOfferStockTransfer {
        if ($stockTransfers) {
            $productOfferStockTransfer->setStock((new StockTransfer())->fromArray($stockTransfers[0], true));

            return $productOfferStockTransfer;
        }

        $productOfferStockTransfer->setStock(
            $this->getStockDataHelper()->haveStock($seed[ProductOfferStockTransfer::STOCK] ?? []),
        );

        return $productOfferStockTransfer;
    }

    /**
     * @return \SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper
     */
    protected function getProductOfferHelper(): ProductOfferHelper
    {
        /** @var \SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper $productOfferHelper */
        $productOfferHelper = $this->getModule('\\' . ProductOfferHelper::class);

        return $productOfferHelper;
    }

    /**
     * @return \SprykerTest\Shared\Stock\Helper\StockDataHelper
     */
    protected function getStockDataHelper(): StockDataHelper
    {
        /** @var \SprykerTest\Shared\Stock\Helper\StockDataHelper $stockDataHelper */
        $stockDataHelper = $this->getModule('\\' . StockDataHelper::class);

        return $stockDataHelper;
    }
}
