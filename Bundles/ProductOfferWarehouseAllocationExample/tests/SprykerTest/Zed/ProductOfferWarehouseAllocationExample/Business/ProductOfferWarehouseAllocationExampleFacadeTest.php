<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferWarehouseAllocationExample\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOfferWarehouseAllocationExample\ProductOfferWarehouseAllocationExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferWarehouseAllocationExample
 * @group Business
 * @group Facade
 * @group ProductOfferWarehouseAllocationExampleFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferWarehouseAllocationExampleFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferWarehouseAllocationExample\ProductOfferWarehouseAllocationExampleBusinessTester
     */
    protected ProductOfferWarehouseAllocationExampleBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $store;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected ProductOfferTransfer $productOffer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferStockTableIsEmpty();
        $this->store = $this->tester->haveStore();
        $this->productOffer = $this->tester->haveProductOffer();
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
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 3,
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer2->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
            ProductOfferStockTransfer::QUANTITY => 1,
        ]);

        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference(),
        );

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
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 4,
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer2->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 3,
        ]);

        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference(),
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
    public function testAllocateSalesOrderWarehouseDoesNotAssignWarehouseWhenProductOfferIsNotProvided(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 4,
        ]);

        $orderTransfer = $this->tester->createOrderTransfer($this->store->getName());

        // Act
        $orderTransfer = $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);

        // Assert
        $this->assertNull($orderTransfer->getItems()->offsetGet(0)->getWarehouse());
    }

    /**
     * @return void
     */
    public function testAllocateSalesOrderWarehouseDoesNotAssignNewWarehouseWhenWarehouseIdIsProvided(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 4,
        ]);

        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference(),
        );
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
    public function testAllocateSalesOrderWarehouseDoesNotAssignWrongWarehouseWhenProductOfferStockNotFound(): void
    {
        // Arrange
        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$this->store->getIdStore()]],
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 4,
        ]);
        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference() . '_test',
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
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $this->productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 1,
        ]);
        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference(),
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
        $orderTransfer = $this->tester->createOrderTransfer(
            null,
            $this->productOffer->getProductOfferReference(),
        );

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
        $orderTransfer = $this->tester->createOrderTransfer(
            $this->store->getName(),
            $this->productOffer->getProductOfferReference(),
        );
        $orderTransfer->getItems()->offsetGet(0)->setQuantity(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->allocateSalesOrderWarehouse($orderTransfer);
    }
}
