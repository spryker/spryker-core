<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group UnassignPickingListsFromUserTest
 * Add your own group annotations below this line
 */
class UnassignPickingListsFromUserTest extends Unit
{
    /**
     * @uses {@link \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE}
     *
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @uses {@link \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED}
     *
     * @var string
     */
    protected const USER_STATUS_BLOCKED = 'blocked';

    /**
     * @uses {@link \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED}
     *
     * @var string
     */
    protected const USER_STATUS_DELETED = 'deleted';

    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @dataProvider ignoresNotSuitableUserDataProvider
     *
     * @param string $userStatus
     * @param bool $isWarehouseUser
     *
     * @return void
     */
    public function testIgnoresNotSuitableUser(string $userStatus, bool $isWarehouseUser): void
    {
        // Arrange
        $initialUserTransfer = $this->tester->haveUser([
            UserTransfer::STATUS => $userStatus,
            UserTransfer::IS_WAREHOUSE_USER => $isWarehouseUser,
        ]);
        $initialUserCollectionTransfer = (new UserCollectionTransfer())->addUser($initialUserTransfer);
        $pickingListTransfer = $this->tester->havePickingList((new PickingListBuilder([
            PickingListTransfer::USER => $initialUserTransfer,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
        ]))->build());

        // Act
        $returnedUserCollectionTransfer = $this->tester->getFacade()->unassignPickingListsFromUsers(clone $initialUserCollectionTransfer);

        // Assert
        $returnedUserTransfer = $returnedUserCollectionTransfer->getUsers()->getIterator()->current();
        $pickingListEntities = $this->tester->getPickingListsAssignedToUser($returnedUserTransfer);
        $this->assertCount(1, $pickingListEntities);
        $this->assertSame(
            $pickingListTransfer->getIdPickingList(),
            $pickingListEntities->getIterator()->current()->getIdPickingList(),
        );
        $this->assertEquals($initialUserCollectionTransfer, $returnedUserCollectionTransfer);
    }

    /**
     * @dataProvider unassignsPickingListFromSuitableUserDataProvider
     *
     * @param string $userStatus
     * @param bool $isWarehouseUser
     *
     * @return void
     */
    public function testUnassignsPickingListFromSuitableUser(string $userStatus, bool $isWarehouseUser): void
    {
        // Arrange
        $initialUserTransfer = $this->tester->haveUser([
            UserTransfer::STATUS => $userStatus,
            UserTransfer::IS_WAREHOUSE_USER => $isWarehouseUser,
        ]);
        $initialUserCollectionTransfer = (new UserCollectionTransfer())->addUser($initialUserTransfer);
        $this->tester->havePickingList((new PickingListBuilder([
            PickingListTransfer::USER => $initialUserTransfer,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
        ]))->build());

        // Act
        $returnedUserCollectionTransfer = $this->tester->getFacade()->unassignPickingListsFromUsers(clone $initialUserCollectionTransfer);

        // Assert
        $returnedUserTransfer = $returnedUserCollectionTransfer->getUsers()->getIterator()->current();
        $pickingListEntities = $this->tester->getPickingListsAssignedToUser($returnedUserTransfer);
        $this->assertCount(0, $pickingListEntities);
        $this->assertEquals($initialUserCollectionTransfer, $returnedUserCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenUserUuidIsMissing(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::UUID => null,
            UserTransfer::STATUS => static::USER_STATUS_BLOCKED,
            UserTransfer::IS_WAREHOUSE_USER => true,
        ]);
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->unassignPickingListsFromUsers($userCollectionTransfer);
    }

    /**
     * @return array<array<string|bool>
     */
    protected function ignoresNotSuitableUserDataProvider(): array
    {
        return [
            [static::USER_STATUS_BLOCKED, false],
            [static::USER_STATUS_DELETED, false],
            [static::USER_STATUS_ACTIVE, true],
        ];
    }

    /**
     * @return array<array<string|bool>
     */
    protected function unassignsPickingListFromSuitableUserDataProvider(): array
    {
        return [
            [static::USER_STATUS_BLOCKED, true],
            [static::USER_STATUS_DELETED, true],
        ];
    }
}
