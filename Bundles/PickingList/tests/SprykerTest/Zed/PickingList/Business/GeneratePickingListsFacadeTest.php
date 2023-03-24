<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\PickingList\Business\Exception\PickingListStrategyNotFoundException;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;
use SprykerTest\Zed\PickingList\Plugin\PickingListGeneratorStrategyPluginMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group GeneratePickingListsFacadeTest
 * Add your own group annotations below this line
 */
class GeneratePickingListsFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PICKING_LIST_STRATEGY = 'TEST_PICKING_LIST_STRATEGY';

    /**
     * @var string
     */
    protected const TEST_PICKING_LIST_STRATEGY_2 = 'TEST_PICKING_LIST_STRATEGY_2';

    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PickingListBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldReturnCollectionWithOnePickingListEntity(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockTransfer->setPickingListStrategy(static::TEST_PICKING_LIST_STRATEGY);
        $orderTransfer = $this->tester->createPersistedOrderTransferExpandedWithWarehouse($stockTransfer);
        $this->mockPickingListGenerator($orderTransfer, $stockTransfer, true);

        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Act
        $pickingListCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);

        // Assert
        $this->assertCount(1, $pickingListCollectionResponseTransfer->getPickingLists());
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldReturnCollectionWithTwoPickingListEntitiesWhenTwoWarehousesSet(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockTransfer->setPickingListStrategy(static::TEST_PICKING_LIST_STRATEGY_2);
        $quoteTransfer = $this->tester->createQuoteTransferWithThreeItems();
        $orderTransfer = $this->tester->createPersistedOrderTransferFromQuote($quoteTransfer);
        $orderTransfer = $this->tester->expandOrderItemsWithWarehouse($orderTransfer, $stockTransfer);
        $orderTransfer = $this->changeWarehouseForFirstPickingListItem($orderTransfer);
        $this->mockPickingListGenerator($orderTransfer, $stockTransfer, true);

        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Act
        $pickingListCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);

        // Assert
        $this->assertCount(2, $pickingListCollectionResponseTransfer->getPickingLists());
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenMissingStrategyPlugin(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockTransfer->setPickingListStrategy(static::TEST_PICKING_LIST_STRATEGY);
        $orderTransfer = $this->tester->createPersistedOrderTransferExpandedWithWarehouse($stockTransfer);
        $this->mockPickingListGenerator($orderTransfer, $stockTransfer, false);

        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(PickingListStrategyNotFoundException::class);

        // Act
        $this->tester->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenOrderItemNotSet(): void
    {
        // Arrange
        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems(new ArrayObject());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(GeneratePickingListsRequestTransfer::class);
        $this->expectExceptionMessage(GeneratePickingListsRequestTransfer::ORDER_ITEMS);

        // Act
        $this->tester->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenOrderItemUuidNotSet(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $orderTransfer = $this->tester->createPersistedOrderTransferExpandedWithWarehouse($stockTransfer);
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setUuid(null);
        }
        $this->mockPickingListGenerator($orderTransfer, $stockTransfer, true);

        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(ItemTransfer::class);
        $this->expectExceptionMessage(ItemTransfer::UUID);

        // Act
        $this->tester->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenWarehouseNotSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createPersistedOrderTransfer();
        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(ItemTransfer::class);
        $this->expectExceptionMessage(ItemTransfer::WAREHOUSE);

        // Act
        $this->tester->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratePickingListsShouldThrowExceptionWhenIdWarehouseNotSet(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock()
            ->setIdStock(null);
        $orderTransfer = $this->tester->createPersistedOrderTransferExpandedWithWarehouse($stockTransfer);
        $this->mockPickingListGenerator($orderTransfer, $stockTransfer, true);

        $generatePickingListsRequestTransfer = (new GeneratePickingListsRequestTransfer())
            ->setOrderItems($orderTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(StockTransfer::class);
        $this->expectExceptionMessage(StockTransfer::ID_STOCK);

        // Act
        $this->tester->getFacade()
            ->generatePickingLists($generatePickingListsRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function changeWarehouseForFirstPickingListItem(OrderTransfer $orderTransfer): OrderTransfer
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();
        $firstItemTransfer = $itemTransferCollection->getIterator()->current();
        if ($firstItemTransfer !== null) {
            $stockTransfer = $this->tester->haveStock();
            $stockTransfer->setPickingListStrategy(static::TEST_PICKING_LIST_STRATEGY);
            $firstItemTransfer->setWarehouse($stockTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param bool $isApplicable
     *
     * @return void
     */
    protected function havePickingListGeneratorStrategyPlugin(
        PickingListTransfer $pickingListTransfer,
        bool $isApplicable
    ): void {
        $this->tester->mockFactoryMethod(
            'getPickingListGeneratorStrategyPlugins',
            [
                $this->createPickingListGeneratorStrategyPluginMock($pickingListTransfer, $isApplicable),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createMockedPickingListTransfer(
        OrderTransfer $orderTransfer,
        StockTransfer $stockTransfer
    ): PickingListTransfer {
        $pickingListTransfer = $this->tester
            ->createPickingListTransfer([
                PickingListTransfer::UUID => null,
                PickingListTransfer::USER => null,
                PickingListTransfer::WAREHOUSE => $stockTransfer,
            ]);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $pickingListItemTransfer = $this->tester
                ->createPickingListItemTransfer([
                    PickingListItemTransfer::UUID => null,
                    PickingListItemTransfer::ORDER_ITEM => $itemTransfer,
                ]);

            $pickingListTransfer->addPickingListItem($pickingListItemTransfer);
        }

        return $pickingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param bool $isApplicable
     *
     * @return void
     */
    protected function mockPickingListGenerator(
        OrderTransfer $orderTransfer,
        StockTransfer $stockTransfer,
        bool $isApplicable
    ): void {
        $mockedPickingListTransfer = $this->createMockedPickingListTransfer(
            $orderTransfer,
            $stockTransfer,
        );

        $this->havePickingListGeneratorStrategyPlugin($mockedPickingListTransfer, $isApplicable);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param bool $isApplicable
     *
     * @return \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface
     */
    protected function createPickingListGeneratorStrategyPluginMock(
        PickingListTransfer $pickingListTransfer,
        bool $isApplicable
    ): PickingListGeneratorStrategyPluginInterface {
        return new PickingListGeneratorStrategyPluginMock(
            $pickingListTransfer,
            $isApplicable,
        );
    }
}
