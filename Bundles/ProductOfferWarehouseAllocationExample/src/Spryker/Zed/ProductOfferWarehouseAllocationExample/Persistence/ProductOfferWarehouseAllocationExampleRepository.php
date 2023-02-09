<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Persistence;

use Generated\Shared\Transfer\ProductOfferWarehouseCriteriaTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\Map\SpyProductOfferStockTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\Persistence\ProductOfferWarehouseAllocationExamplePersistenceFactory getFactory()
 */
class ProductOfferWarehouseAllocationExampleRepository extends AbstractRepository implements ProductOfferWarehouseAllocationExampleRepositoryInterface
{
    /**
     * @module ProductOffer
     * @module ProductOfferStock
     * @module Stock
     * @module Store
     *
     * @param \Generated\Shared\Transfer\ProductOfferWarehouseCriteriaTransfer $productOfferWarehouseCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findProductOfferWarehouse(
        ProductOfferWarehouseCriteriaTransfer $productOfferWarehouseCriteriaTransfer
    ): ?StockTransfer {
        /** @var literal-string $whereMaxQuantity */
        $whereMaxQuantity = sprintf(
            '%s %s %d',
            SpyProductOfferStockTableMap::COL_QUANTITY,
            Criteria::GREATER_EQUAL,
            (int)$productOfferWarehouseCriteriaTransfer->getQuantityOrFail(),
        );

        $productOfferStockEntity = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->useStockQuery()
                ->useStockStoreQuery()
                    ->useStoreQuery()
                        ->filterByName($productOfferWarehouseCriteriaTransfer->getStore())
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductOfferQuery()
                ->filterByProductOfferReference($productOfferWarehouseCriteriaTransfer->getProductOfferReference())
            ->endUse()
            ->where(SpyProductOfferStockTableMap::COL_IS_NEVER_OUT_OF_STOCK)
            ->_or()
            ->where($whereMaxQuantity)
            ->orderBy(SpyProductOfferStockTableMap::COL_IS_NEVER_OUT_OF_STOCK, Criteria::DESC)
            ->orderBy(SpyProductOfferStockTableMap::COL_QUANTITY, Criteria::DESC)
            ->findOne();

        if (!$productOfferStockEntity) {
            return null;
        }

        return (new StockTransfer())->fromArray($productOfferStockEntity->getStock()->toArray());
    }
}
