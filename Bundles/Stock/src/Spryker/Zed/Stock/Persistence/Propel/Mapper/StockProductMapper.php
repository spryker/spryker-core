<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Stock\Persistence\SpyStockProduct;

class StockProductMapper
{
    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct[] $stockProductEntities
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function mapStockProductEntitiesToStockProductTransfers(array $stockProductEntities): array
    {
        $stockProductTransfers = [];
        foreach ($stockProductEntities as $stockProductEntity) {
            $stockProductTransfers[] = $this->mapStockProductEntityToStockProductTransfer(
                $stockProductEntity,
                new StockProductTransfer()
            );
        }

        return $stockProductTransfers;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct $stockProductEntity
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function mapStockProductEntityToStockProductTransfer(
        SpyStockProduct $stockProductEntity,
        StockProductTransfer $stockProductTransfer
    ): StockProductTransfer {
        $stockProductTransfer->fromArray($stockProductEntity->toArray(), true);
        $stockProductTransfer->setSku($stockProductEntity->getSpyProduct()->getSku());
        $stockProductTransfer->setStockType($stockProductEntity->getStock()->getName());

        return $stockProductTransfer;
    }
}
