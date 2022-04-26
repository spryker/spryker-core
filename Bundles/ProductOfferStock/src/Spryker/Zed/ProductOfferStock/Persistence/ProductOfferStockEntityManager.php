<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockPersistenceFactory getFactory()
 */
class ProductOfferStockEntityManager extends AbstractEntityManager implements ProductOfferStockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function create(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer
    {
        $productOfferStockMapper = $this->getFactory()
            ->createProductOfferStockMapper();

        $productOfferStockEntity = $productOfferStockMapper
            ->mapProductOfferStockTransferToProductOfferStockEntity($productOfferStockTransfer, new SpyProductOfferStock());

        $productOfferStockEntity->save();

        return $productOfferStockMapper
            ->mapProductOfferStockEntityToProductOfferStockTransfer($productOfferStockEntity, $productOfferStockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function update(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer
    {
        $productOfferStockTransfer->requireIdProductOfferStock();

        /** @var int $idProductOfferStock */
        $idProductOfferStock = $productOfferStockTransfer->getIdProductOfferStock();

        /** @var \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock $productOfferStockEntity */
        $productOfferStockEntity = $this->getFactory()->getProductOfferStockPropelQuery()->findOneByIdProductOfferStock(
            $idProductOfferStock,
        );

        $productOfferStockMapper = $this->getFactory()->createProductOfferStockMapper();
        $productOfferStockEntity = $productOfferStockMapper->mapProductOfferStockTransferToProductOfferStockEntity(
            $productOfferStockTransfer,
            $productOfferStockEntity,
        );
        $productOfferStockEntity->save();

        return $productOfferStockMapper->mapProductOfferStockEntityToProductOfferStockTransfer(
            $productOfferStockEntity,
            $productOfferStockTransfer,
        );
    }
}
