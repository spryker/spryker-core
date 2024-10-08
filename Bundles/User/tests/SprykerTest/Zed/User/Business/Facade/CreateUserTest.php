<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\UserDependencyProvider;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface;
use SprykerTest\Zed\User\UserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Business
 * @group Facade
 * @group CreateUserTest
 * Add your own group annotations below this line
 */
class CreateUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\User\UserBusinessTester
     */
    public UserBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesUserInDatabase(): void
    {
        // Arrange
        $userTransfer = $this->tester->getUserTransfer();

        // Act
        $this->tester->getFacade()->createUser($userTransfer);

        // Assert
        $this->assertInstanceOf(UserTransfer::class, $this->tester->findUserByUserName($userTransfer->getUsername()));
    }

    /**
     * @return void
     */
    public function testReturnsCorrectUserData(): void
    {
        // Arrange
        $userTransfer = $this->tester->getUserTransfer();

        // Act
        $persistedUserTransfer = $this->tester->getFacade()->createUser($userTransfer);

        // Assert
        $this->assertInstanceOf(UserTransfer::class, $persistedUserTransfer);
        $this->assertNotNull($persistedUserTransfer->getIdUser());
        $this->assertSame($userTransfer->getFirstName(), $persistedUserTransfer->getFirstName());
        $this->assertSame($userTransfer->getLastName(), $persistedUserTransfer->getLastName());
        $this->assertSame($userTransfer->getUsername(), $persistedUserTransfer->getUsername());
        $this->assertNotEquals($userTransfer->getPassword(), $persistedUserTransfer->getPassword());
    }

    /**
     * @return void
     */
    public function testExecutesUserPostSavePluginStack(): void
    {
        // Arrange
        $userTransfer = $this->tester->getUserTransfer();
        $this->tester->setDependency(
            UserDependencyProvider::PLUGINS_POST_SAVE,
            [$this->getUserPostSavePluginMock()],
        );

        // Act
        $this->tester->getFacade()->createUser($userTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesUserPostCreatePluginStack(): void
    {
        // Arrange
        $userTransfer = $this->tester->getUserTransfer();
        $this->tester->setDependency(
            UserDependencyProvider::PLUGINS_USER_POST_CREATE,
            [$this->getUserPostCreatePluginMock($userTransfer)],
        );

        // Act
        $this->tester->getFacade()->createUser($userTransfer);
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface
     */
    protected function getUserPostSavePluginMock(): UserPostSavePluginInterface
    {
        $userPostSavePluginMock = $this->getMockBuilder(UserPostSavePluginInterface::class)->getMock();
        $userPostSavePluginMock->expects($this->once())->method('postSave');

        return $userPostSavePluginMock;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface
     */
    protected function getUserPostCreatePluginMock(UserTransfer $userTransfer): UserPostCreatePluginInterface
    {
        $userPostCreatePluginMock = $this->getMockBuilder(UserPostCreatePluginInterface::class)->getMock();
        $userPostCreatePluginMock->method('postCreate')->willReturn(
            (new UserCollectionResponseTransfer())->addUser($userTransfer),
        );
        $userPostCreatePluginMock->expects($this->once())->method('postCreate');

        return $userPostCreatePluginMock;
    }
}
