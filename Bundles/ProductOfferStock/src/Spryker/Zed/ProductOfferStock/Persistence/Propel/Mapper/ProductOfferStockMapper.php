<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\Stock\Persistence\SpyStock;
use Propel\Runtime\Collection\ObjectCollection;

class ProductOfferStockMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productOfferStockEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[] $productOfferStockTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[]
     */
    public function mapProductOfferStockEntityCollectionToProductOfferStockTransfers(
        ObjectCollection $productOfferStockEntities,
        ArrayObject $productOfferStockTransfers
    ): ArrayObject {
        foreach ($productOfferStockEntities as $productOfferStockEntity) {
            $productOfferStockTransfers->append(
                $this->mapProductOfferStockEntityToProductOfferStockTransfer($productOfferStockEntity, new ProductOfferStockTransfer())
            );
        }

        return $productOfferStockTransfers;
    }

    /**
     * @param \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock $productOfferStockEntity
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function mapProductOfferStockEntityToProductOfferStockTransfer(
        SpyProductOfferStock $productOfferStockEntity,
        ProductOfferStockTransfer $productOfferStockTransfer
    ): ProductOfferStockTransfer {
        $productOfferStockTransfer->fromArray($productOfferStockEntity->toArray(), true);
        $productOfferStockTransfer->setIdProductOffer($productOfferStockEntity->getFkProductOffer());
        $productOfferStockTransfer->setProductOfferReference($productOfferStockEntity->getSpyProductOffer()->getProductOfferReference());
        $productOfferStockTransfer->setStock(
            $this->mapStockEntityToStockTransfer($productOfferStockEntity->getStock(), new StockTransfer())
        );

        return $productOfferStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock $productOfferStockEntity
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock
     */
    public function mapProductOfferStockTransferToProductOfferStockEntity(
        ProductOfferStockTransfer $productOfferStockTransfer,
        SpyProductOfferStock $productOfferStockEntity
    ): SpyProductOfferStock {
        $productOfferStockEntity->fromArray($productOfferStockTransfer->toArray(false));
        $productOfferStockEntity->setFkProductOffer($productOfferStockTransfer->getIdProductOffer());
        $productOfferStockEntity->setFkStock($productOfferStockTransfer->getStock()->getIdStock());

        return $productOfferStockEntity;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapStockEntityToStockTransfer(SpyStock $stockEntity, StockTransfer $stockTransfer): StockTransfer
    {
        return $stockTransfer->fromArray($stockEntity->toArray(), true);
    }
}
