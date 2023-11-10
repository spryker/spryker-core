<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductWarehouseAllocationExample;

use Codeception\Actor;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductWarehouseAllocationExample\PHPMD)
 *
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Business\ProductWarehouseAllocationExampleFacadeInterface getFacade(?string $moduleName = null)
 */
class ProductWarehouseAllocationExampleBusinessTester extends Actor
{
    use _generated\ProductWarehouseAllocationExampleBusinessTesterActions;

    /**
     * @param string|null $storeName
     * @param string|null $sku
     * @param int|null $quantity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransfer(?string $storeName = null, ?string $sku = null, ?int $quantity = 1): OrderTransfer
    {
        return (new OrderTransfer())
            ->setStore($storeName)
            ->addItem(
                (new ItemTransfer())
                    ->setSku($sku)
                    ->setQuantity($quantity),
            );
    }

    /**
     * @return void
     */
    public function ensureStockProductTableIsEmpty(): void
    {
        $stockProductQuery = $this->getStockProductQuery();
        $this->ensureDatabaseTableIsEmpty($stockProductQuery);
        $stockProductQuery->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProduct
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param bool $isNeverOutOfStock
     * @param int $quantity
     *
     * @return void
     */
    public function addStockProduct(
        StockProductTransfer $stockProduct,
        StockTransfer $stockTransfer,
        bool $isNeverOutOfStock = false,
        int $quantity = 2
    ): void {
        $this->haveStockProduct([
            StockProductTransfer::STOCK_TYPE => $stockTransfer->getName(),
            StockProductTransfer::FK_STOCK => $stockTransfer->getIdStock(),
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => $isNeverOutOfStock,
            StockProductTransfer::QUANTITY => $quantity,
            StockProductTransfer::SKU => $stockProduct->getSku(),
        ]);
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    protected function getStockProductQuery(): SpyStockProductQuery
    {
        return SpyStockProductQuery::create();
    }
}
