<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingListMultiShipmentPickingStrategyExample
 * @group Business
 * @group Facade
 * @group GeneratePickingListsFacadeTest
 * Add your own group annotations below this line
 */
class GeneratePickingListsFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleBusinessTester
     */
    protected PickingListMultiShipmentPickingStrategyExampleBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PickingListMultiShipmentPickingStrategyExampleBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldReturnCollectionWithOnePickingListEntity(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $orderTransfer = $this->tester->expandOrderItemsWithShipment($orderTransfer);
        $stockTransfer = $this->tester->createStockTransfer();

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer)
            ->setOrderItems($orderTransfer->getItems());

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->generatePickingLists($pickingListOrderItemGroupTransfer);

        // Assert
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionTransfer->getPickingLists();
        $this->assertCount(1, $pickingListTransferCollection);

        $pickingListTransfer = $pickingListTransferCollection->getIterator()->current();
        $this->assertNotNull($pickingListTransfer->getWarehouse());
        $this->assertSame($stockTransfer->getIdStock(), $pickingListTransfer->getWarehouseOrFail()->getIdStock());
        $this->assertNull($pickingListTransfer->getStatus());

        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $this->assertEquals(0, $pickingListItemTransfer->getNumberOfPicked());
            $this->assertEquals(0, $pickingListItemTransfer->getNumberOfNotPicked());
            $this->assertNotEquals(0, $pickingListItemTransfer->getQuantity());
            $this->assertNotNull($pickingListItemTransfer->getOrderItem());
        }
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldReturnCollectionWithTwoPickingListEntitiesWhenTwoShipmentsSet(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithThreeItems();
        $orderTransfer = $this->tester->createPersistedOrderTransferFromQuote($quoteTransfer);
        $orderTransfer = $this->tester->expandOrderItemsWithShipment($orderTransfer);
        $orderTransfer = $this->changeShipmentForFirstPickingListItem($orderTransfer);
        $stockTransfer = $this->tester->createStockTransfer();

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer)
            ->setOrderItems($orderTransfer->getItems());

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->generatePickingLists($pickingListOrderItemGroupTransfer);

        // Assert
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionTransfer->getPickingLists();
        $this->assertCount(2, $pickingListTransferCollection);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldReturnEmptyCollectionWhenOrderItemsNotSet(): void
    {
        // Arrange
        $stockTransfer = $this->tester->createStockTransfer();
        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer);

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->generatePickingLists($pickingListOrderItemGroupTransfer);

        // Assert
        $this->assertCount(0, $pickingListCollectionTransfer->getPickingLists());
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenShipmentNotSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $stockTransfer = $this->tester->createStockTransfer();

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer)
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(ItemTransfer::class);
        $this->expectExceptionMessage(ItemTransfer::SHIPMENT);

        // Act
        $this->tester->getFacade()->generatePickingLists($pickingListOrderItemGroupTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenWarehouseNotSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $orderTransfer = $this->tester->expandOrderItemsWithShipment($orderTransfer);

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(PickingListOrderItemGroupTransfer::class);
        $this->expectExceptionMessage(PickingListOrderItemGroupTransfer::WAREHOUSE);

        // Act
        $this->tester->getFacade()->generatePickingLists($pickingListOrderItemGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function changeShipmentForFirstPickingListItem(OrderTransfer $orderTransfer): OrderTransfer
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();
        $firstItemTransfer = $itemTransferCollection->getIterator()->current();
        if ($firstItemTransfer !== null) {
            $shipmentTransfer = $this->tester
                ->createShipmentTransfer()
                ->setRequestedDeliveryDate(
                    (new DateTime('tomorrow'))->format('Y-m-d'),
                );
            $firstItemTransfer->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }
}
