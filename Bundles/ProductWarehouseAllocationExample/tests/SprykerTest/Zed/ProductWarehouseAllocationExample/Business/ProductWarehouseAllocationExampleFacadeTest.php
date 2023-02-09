<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductWarehouseAllocationExample\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductWarehouseAllocationExample\ProductWarehouseAllocationExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductWarehouseAllocationExample
 * @group Business
 * @group Facade
 * @group ProductWarehouseAllocationExampleFacadeTest
 * Add your own group annotations below this line
 */
class ProductWarehouseAllocationExampleFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductWarehouseAllocationExample\ProductWarehouseAllocationExampleBusinessTester
     */
    protected ProductWarehouseAllocationExampleBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $store;

    /**
     * @var \Generated\Shared\Transfer\StockProductTransfer
     */
    protected StockProductTransfer $stockProduct;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureStockProductTableIsEmpty();
        $this->store = $this->tester->haveStore();
        $stockTransfer = $this->tester->haveStock();
        $productTransfer = $this->tester->haveProduct();
        $this->stockProduct = $this->tester->haveStockProduct([
            StockProductTransfer::STOCK_TYPE => $stockTransfer->getName(),
            StockProductTransfer::SKU => $productTransfer->getSku(),
        ]);
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseAssignsWarehouseWithNeverOutOfStock(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $stockTransfer2 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer1);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer2, true, 0);

        $orderTransfer = $this->tester->createOrderTransfer($this->store->getName(), $this->stockProduct->getSku());

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertSame(
            $stockTransfer2->getIdStock(),
            $orderTransfer->getItems()->offsetGet(0)->getWarehouse()->getIdStock(),
        );
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseAssignsWarehouseWithHighestQuantity(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $stockTransfer2 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);

        $this->tester->addStockProduct($this->stockProduct, $stockTransfer1, false, 4);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer2, false, 3);

        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->stockProduct->getSku(),
        );

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertSame(
            $stockTransfer1->getIdStock(),
            $orderTransfer->getItems()->offsetGet(0)->getWarehouse()->getIdStock(),
        );
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseDoesNotAssignNewWarehouseWhenWarehouseUuidIsProvided(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer1);

        $orderTransfer = $this->tester->createOrderTransfer($this->store->getName(), $this->stockProduct->getSku());
        $orderTransfer->getItems()->offsetGet(0)->setWarehouse((new StockTransfer())->setIdStock(1));

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertSame(
            1,
            $orderTransfer->getItems()->offsetGet(0)->getWarehouse()->getIdStock(),
        );
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseDoesNotAssignWrongWarehouseWhenStockProductNotFound(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer1);
        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->stockProduct->getSku() . '_test',
        );

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertNull($orderTransfer->getItems()->offsetGet(0)->getWarehouse());
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseDoesNotAssignWrongWarehouseWhenQuantityIsNotEnough(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->addStockProduct($this->stockProduct, $stockTransfer1, false, 1);
        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->stockProduct->getSku(),
            2,
        );

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertNull($orderTransfer->getItems()->offsetGet(0)->getWarehouse());
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseThrowsExceptionWhenStoreIsNotSetToOrderTransfer(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransfer(null, $this->stockProduct->getSku());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseThrowsExceptionWhenItemQuantityIsNotSetToOrderTransfer(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransfer($this->store->getName(), $this->stockProduct->getSku(), null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseThrowsExceptionWhenItemSkuIsNotSetToOrderTransfer(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderTransfer($this->store->getName());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);
    }
}
