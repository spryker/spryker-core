<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Persistence;

use Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExamplePersistenceFactory getFactory()
 */
class ProductWarehouseAllocationExampleRepository extends AbstractRepository implements ProductWarehouseAllocationExampleRepositoryInterface
{
    /**
     * @module Product
     * @module Stock
     * @module Store
     *
     * @param \Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer $productWarehouseCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findProductWarehouse(ProductWarehouseCriteriaTransfer $productWarehouseCriteriaTransfer): ?StockTransfer
    {
        /** @var literal-string $whereMaxQuantity */
        $whereMaxQuantity = sprintf(
            '%s %s %d',
            SpyStockProductTableMap::COL_QUANTITY,
            Criteria::GREATER_EQUAL,
            (int)$productWarehouseCriteriaTransfer->getQuantityOrFail(),
        );

        $stockProductEntity = $this->getFactory()
            ->getStockProductQuery()
            ->useStockQuery()
                ->useStockStoreQuery()
                    ->useStoreQuery()
                        ->filterByName($productWarehouseCriteriaTransfer->getStoreNameOrFail())
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductQuery()
                ->filterBySku($productWarehouseCriteriaTransfer->getSkuOrFail())
            ->endUse()
            ->where(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK)
            ->_or()
            ->where($whereMaxQuantity)
            ->orderBy(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK, Criteria::DESC)
            ->orderBy(SpyStockProductTableMap::COL_QUANTITY, Criteria::DESC)
            ->findOne();

        if (!$stockProductEntity) {
            return null;
        }

        return (new StockTransfer())->fromArray($stockProductEntity->getStock()->toArray(), true);
    }
}
