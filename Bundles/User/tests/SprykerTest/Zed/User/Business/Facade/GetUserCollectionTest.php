<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\UserDependencyProvider;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface;
use SprykerTest\Zed\User\UserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Business
 * @group Facade
 * @group GetUserCollectionTest
 * Add your own group annotations below this line
 */
class GetUserCollectionTest extends Unit
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     *
     * @var string
     */
    protected const USER_STATUS_BLOCKED = 'blocked';

    /**
     * @var \SprykerTest\Zed\User\UserBusinessTester
     */
    public UserBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureUserTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfUserTransfersById(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($userTransfer->getIdUserOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        $this->assertSame($userTransfer->getIdUserOrFail(), $userCollectionTransfer->getUsers()->getIterator()->current()->getIdUser());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfUserTransfersByUsername(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($userTransfer->getUsername());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        $this->assertSame($userTransfer->getIdUserOrFail(), $userCollectionTransfer->getUsers()->getIterator()->current()->getIdUser());
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfUserTransfersByStatus(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_BLOCKED]);
        $userConditionsTransfer = (new UserConditionsTransfer())->addStatus(static::USER_STATUS_BLOCKED);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        $this->assertSame($userTransfer->getIdUserOrFail(), $userCollectionTransfer->getUsers()->getIterator()->current()->getIdUser());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenUsersNotFoundAndThrowExceptionConditionIsSetToTrue(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->addIdUser(0)
            ->setThrowUserNotFoundException(true);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Assert
        $this->expectException(UserNotFoundException::class);

        // Act
        $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsCollectionOfUserTransfersByUuid(): void
    {
        // Arrange
        if (!SpyUserTableMap::getTableMap()->hasColumn('uuid')) {
            $this->markTestSkipped('This test requires uuid column in spy_user table.');
        }

        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userTransfer->getUuidOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());
        $this->assertSame($userTransfer->getIdUserOrFail(), $userCollectionTransfer->getUsers()->getIterator()->current()->getIdUser());
        $this->assertSame($userTransfer->getUuidOrFail(), $userCollectionTransfer->getUsers()->getIterator()->current()->getUuid());
    }

    /**
     * @return void
     */
    public function testReturnsEmptyCollectionOfUserTransfersByInvalidId(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser(0);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);

        // Assert
        $this->assertCount(0, $userCollectionTransfer->getUsers());
    }

    /**
     * @return void
     */
    public function testExecutesUserExpanderPlugins(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userTransfer->getUuidOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Assert
        $userExpanderPluginMock = $this
            ->getMockBuilder(UserExpanderPluginInterface::class)
            ->getMock();

        $userExpanderPluginMock
            ->expects($this->once())
            ->method('expand');

        $this->tester->setDependency(UserDependencyProvider::PLUGINS_USER_EXPANDER, [$userExpanderPluginMock]);

        // Act
        $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesUserTransferExpanderPlugins(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userTransfer->getUuidOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Assert
        $userTransferExpanderPluginMock = $this
            ->getMockBuilder(UserTransferExpanderPluginInterface::class)
            ->getMock();

        $userTransferExpanderPluginMock
            ->expects($this->once())
            ->method('expandUserTransfer');

        $this->tester->setDependency(UserDependencyProvider::PLUGINS_USER_TRANSFER_EXPANDER, [$userTransferExpanderPluginMock]);

        // Act
        $this->tester->getFacade()->getUserCollection($userCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesUserQueryCriteriaExpanderPlugins(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $this->tester->haveUser();

        $this->tester->setDependency(UserDependencyProvider::PLUGINS_USER_QUERY_CRITERIA_EXPANDER, [
            $this->createUserQueryCriteriaExpanderPlugin($userTransfer->getIdUserOrFail()),
        ]);

        // Act
        $userCollectionTransfer = $this->tester->getFacade()->getUserCollection(new UserCriteriaTransfer());

        // Assert
        $this->assertCount(1, $userCollectionTransfer->getUsers());

        /** @var \Generated\Shared\Transfer\UserTransfer $resultUserTransfer */
        $resultUserTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $this->assertSame($userTransfer->getIdUserOrFail(), $resultUserTransfer->getIdUserOrFail());
    }

    /**
     * @param int $idUser
     *
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface
     */
    public function createUserQueryCriteriaExpanderPlugin(int $idUser): UserQueryCriteriaExpanderPluginInterface
    {
        return new class ($idUser) implements UserQueryCriteriaExpanderPluginInterface {
            /**
             * @var int
             */
            protected int $idUser;

            /**
             * @param int $idUser
             */
            public function __construct(int $idUser)
            {
                $this->idUser = $idUser;
            }

            /**
             * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
             * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
             *
             * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
             */
            public function expand(QueryCriteriaTransfer $queryCriteriaTransfer, UserCriteriaTransfer $userCriteriaTransfer): QueryCriteriaTransfer
            {
                return $queryCriteriaTransfer->setConditions([
                    sprintf('%s = ?', SpyUserTableMap::COL_ID_USER) => $this->idUser,
                ]);
            }
        };
    }
}
