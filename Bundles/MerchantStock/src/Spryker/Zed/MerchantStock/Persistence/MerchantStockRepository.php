<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockRepository extends AbstractRepository implements MerchantStockRepositoryInterface
{
    /**
     * @module Stock
     *
     * @param \Generated\Shared\Transfer\MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function get(MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer): StockCollectionTransfer
    {
        $merchantStockQuery = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->leftJoinWithSpyStock()
            ->filterByFkMerchant($merchantStockCriteriaTransfer->getIdMerchantOrFail());

        if ($merchantStockCriteriaTransfer->getIsDefault()) {
            $merchantStockQuery->filterByIsDefault(true);
        }

        $merchantStocksEntities = $merchantStockQuery->find();

        $stockCollectionTransfer = new StockCollectionTransfer();
        $merchantStockMapper = $this->getFactory()->createMerchantStockMapper();

        foreach ($merchantStocksEntities as $merchantStockEntity) {
            $stockCollectionTransfer->addStock(
                $merchantStockMapper->mapStockEntityToStockTransfer($merchantStockEntity->getSpyStock(), new StockTransfer()),
            );
        }

        return $stockCollectionTransfer;
    }

    /**
     * @module Stock
     *
     * @param list<int> $merchantIds
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer>>
     */
    public function getStocksGroupedByIdMerchant(array $merchantIds): array
    {
        if (!$merchantIds) {
            return [];
        }

        $merchantStocksEntities = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->leftJoinWithSpyStock()
            ->filterByFkMerchant_In($merchantIds)
            ->find();

        return $this->getFactory()->createMerchantStockMapper()
            ->mapMerchantStockEntityCollectionToStocksGroupedByIdMerchant($merchantStocksEntities);
    }
}
