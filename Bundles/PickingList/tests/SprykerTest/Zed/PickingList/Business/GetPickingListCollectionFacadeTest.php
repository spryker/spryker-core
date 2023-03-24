<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListCriteriaBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group GetPickingListCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetPickingListCollectionFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const DUMMY_PAYMENT_STATE_MACHINE_PROCESS_NAME = 'DummyPayment01';

    /**
     * @var string
     */
    protected const TEST_UUID = 'TEST_UUID';

    /**
     * @var string
     */
    protected const TEST_UUID_2 = 'TEST_UUID_2';

    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnEmptyCollectionWhenNoEntityMatchedByCriteria(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
        ]);
        $this->tester->havePickingList($pickingListTransfer);
        $pickingListTransfer = $this->tester->createPickingListTransfer();
        $pickingListCriteriaTransfer = $this->createPickingListCriteriaTransfer($pickingListTransfer);

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->getPickingListCollection($pickingListCriteriaTransfer);

        // Assert
        $this->assertPickingListCollectionIsEmpty($pickingListCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnCollectionWithOnePickingListEntityWhenCriteriaMatched(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::USER => $this->tester->haveUser(),
        ]);
        $pickingListTransfer = $this->tester->havePickingList($pickingListTransfer);
        $pickingListCriteriaTransfer = $this->createPickingListCriteriaTransfer($pickingListTransfer);

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->getPickingListCollection($pickingListCriteriaTransfer);

        // Assert
        $this->assertPickingListCollectionContainsTransferWithId($pickingListCollectionTransfer, $pickingListTransfer);
    }

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnCollectionWithTwoPickingListEntityWithUserUuidAndWithUnassignedUserFilters(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListWithWarehouse();
        $pickingListTransfer2 = $this->createPickingListWithWarehouse(false);

        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions(
                (new PickingListConditionsTransfer())
                    ->addUuid($pickingListTransfer->getUuid())
                    ->addUuid($pickingListTransfer2->getUuid())
                    ->setWithUnassignedUser(true)
                    ->addUserUuid($pickingListTransfer->getUser()->getUuid()),
            );

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->getPickingListCollection($pickingListCriteriaTransfer);

        // Assert
        $this->assertCount(2, $pickingListCollectionTransfer->getPickingLists());
        $this->assertPickingListCollectionContainsTransferWithId($pickingListCollectionTransfer, $pickingListTransfer);
        $this->assertPickingListCollectionContainsTransferWithId($pickingListCollectionTransfer, $pickingListTransfer2);
    }

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnCollectionWithOnePickingListEntityWithUnassignedUserFilters(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListWithWarehouse();
        $pickingListTransfer2 = $this->createPickingListWithWarehouse(false);

        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions(
                (new PickingListConditionsTransfer())
                    ->addUuid($pickingListTransfer->getUuid())
                    ->addUuid($pickingListTransfer2->getUuid())
                    ->setWithUnassignedUser(true),
            );

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()
            ->getPickingListCollection($pickingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pickingListCollectionTransfer->getPickingLists());
        $this->assertPickingListCollectionContainsTransferWithId($pickingListCollectionTransfer, $pickingListTransfer2);
    }

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnCollectionExpandedWithOrderItemsWhenCriteriaMatched(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::UUID => static::TEST_UUID]))->build();
        $this->tester->haveOrder($itemTransfer->toArray(), static::DUMMY_PAYMENT_STATE_MACHINE_PROCESS_NAME);

        $pickingListItemTransfer = (new PickingListItemBuilder())->build()->setOrderItem($itemTransfer);
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [$pickingListItemTransfer],
        ]);
        $pickingListTransfer = $this->tester->havePickingList($pickingListTransfer);
        $pickingListCriteriaTransfer = $this->createPickingListCriteriaTransfer($pickingListTransfer);

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()->getPickingListCollection($pickingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pickingListCollectionTransfer->getPickingLists());

        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        $pickingListTransfer = $pickingListCollectionTransfer->getPickingLists()->getIterator()->current();
        $this->assertCount(1, $pickingListTransfer->getPickingListItems());

        /** @var \Generated\Shared\Transfer\PickingListItemTransfer $resultPickingListItemTransfer */
        $resultPickingListItemTransfer = $pickingListTransfer->getPickingListItems()->getIterator()->current();
        $this->assertNotNull($resultPickingListItemTransfer->getOrderItem());
        $this->assertNotNull($resultPickingListItemTransfer->getOrderItem()->getIdSalesOrderItem());
        $this->assertSame(
            $pickingListItemTransfer->getOrderItemOrFail()->getUuidOrFail(),
            $resultPickingListItemTransfer->getOrderItem()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testGetPickingListShouldReturnCollectionsOnlyWithRequestedPickingListItems(): void
    {
        // Arrange
        $expectedPickingListItemTransfer = (new PickingListItemBuilder())->build()
            ->setOrderItem((new ItemBuilder([
                ItemTransfer::UUID => static::TEST_UUID,
            ]))->build());
        $expectedSecondPickingListItemTransfers = (new PickingListItemBuilder())->build()
            ->setOrderItem((new ItemBuilder([
                ItemTransfer::UUID => static::TEST_UUID_2,
            ]))->build());

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [$expectedPickingListItemTransfer, $expectedSecondPickingListItemTransfers],
        ]);
        $this->tester->havePickingList($pickingListTransfer);

        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())->setPickingListConditions(
            (new PickingListConditionsTransfer())->addSalesOrderItemUuid(static::TEST_UUID),
        );
        $secondPickingListCriteriaTransfer = (new PickingListCriteriaTransfer())->setPickingListConditions(
            (new PickingListConditionsTransfer())->addSalesOrderItemUuid(static::TEST_UUID_2),
        );

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()->getPickingListCollection($pickingListCriteriaTransfer);
        $secondPickingListCollectionTransfer = $this->tester->getFacade()->getPickingListCollection($secondPickingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $pickingListCollectionTransfer->getPickingLists());

        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        $pickingListTransfer = $pickingListCollectionTransfer->getPickingLists()->getIterator()->current();
        $this->assertCount(1, $pickingListTransfer->getPickingListItems());

        /** @var \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer */
        $pickingListItemTransfer = $pickingListTransfer->getPickingListItems()->getIterator()->current();
        $this->assertSame(static::TEST_UUID, $pickingListItemTransfer->getOrderItem()->getUuid());

        $this->assertCount(1, $secondPickingListCollectionTransfer->getPickingLists());

        /** @var \Generated\Shared\Transfer\PickingListTransfer $secondPickingListTransfer */
        $secondPickingListTransfer = $secondPickingListCollectionTransfer->getPickingLists()->getIterator()->current();
        $this->assertCount(1, $secondPickingListTransfer->getPickingListItems());

        /** @var \Generated\Shared\Transfer\PickingListItemTransfer $secondPickingListItemTransfer */
        $secondPickingListItemTransfer = $secondPickingListTransfer->getPickingListItems()->getIterator()->current();
        $this->assertSame(static::TEST_UUID_2, $secondPickingListItemTransfer->getOrderItem()->getUuid());
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return void
     */
    protected function assertPickingListCollectionIsEmpty(PickingListCollectionTransfer $pickingListCollectionTransfer): void
    {
        $this->assertCount(
            0,
            $pickingListCollectionTransfer->getPickingLists(),
            sprintf(
                'Expected to have an empty collection but found "%s" items',
                $pickingListCollectionTransfer->getPickingLists()->count(),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return void
     */
    protected function assertPickingListCollectionContainsTransferWithId(
        PickingListCollectionTransfer $pickingListCollectionTransfer,
        PickingListTransfer $pickingListTransfer
    ): void {
        $transferFound = false;

        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransferFromCollection) {
            if ($pickingListTransferFromCollection->getIdPickingList() === $pickingListTransfer->getIdPickingList()) {
                $transferFound = true;
            }
        }

        $this->assertTrue(
            $transferFound,
            sprintf(
                'Expected to have a transfer in the collection but transfer by id "%s" was not found in the collection',
                $pickingListTransfer->getIdPickingList(),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    protected function createPickingListCriteriaTransfer(PickingListTransfer $pickingListTransfer): PickingListCriteriaTransfer
    {
        $seed = [
            PickingListConditionsTransfer::UUIDS => [
                $pickingListTransfer->getUuid(),
            ],
            PickingListConditionsTransfer::WAREHOUSE_UUIDS => [
                $pickingListTransfer->getWarehouse()->getUuid(),
            ],
            PickingListConditionsTransfer::USER_UUIDS => [
                $pickingListTransfer->getUser()->getUuid(),
            ],
            PickingListConditionsTransfer::STATUSES => [
                $pickingListTransfer->getStatus(),
            ],
        ];

        return (new PickingListCriteriaBuilder())->withPickingListConditions($seed)->build();
    }

    /**
     * @param bool $withUser
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListWithWarehouse(bool $withUser = true): PickingListTransfer
    {
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::USER => $withUser ? $this->tester->haveUser() : null,
        ]);

        return $this->tester->havePickingList($pickingListTransfer);
    }
}
