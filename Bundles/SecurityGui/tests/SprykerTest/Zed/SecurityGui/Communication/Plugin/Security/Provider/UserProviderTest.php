<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Plugin\Security\Provider;

use Codeception\Test\Unit;
use Spryker\Zed\SecurityGui\Communication\Exception\AccessDeniedException;
use Spryker\Zed\SecurityGui\SecurityGuiDependencyProvider;
use Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface;
use SprykerTest\Zed\SecurityGui\SecurityGuiCommunicationTester;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Provider
 * @group UserProviderTest
 * Add your own group annotations below this line
 */
class UserProviderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SecurityGui\SecurityGuiCommunicationTester
     */
    protected SecurityGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testLoadUserByUsernameThrowsExceptionWhenUserIsRestrictedByUserLoginRestrictionPlugin(): void
    {
        // Arrange
        $this->tester->setDependency(
            SecurityGuiDependencyProvider::PLUGINS_USER_LOGIN_RESTRICTION,
            [$this->getUserLoginRestrictionPluginMock(true)],
        );
        $userTransfer = $this->tester->haveUser();

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->tester->getUser($userTransfer->getUsernameOrFail());
    }

    /**
     * @return void
     */
    public function testLoadUserByUsernameReturnsUserWhenUserIsNotRestrictedByUserLoginRestrictionPlugin(): void
    {
        // Arrange
        $this->tester->setDependency(
            SecurityGuiDependencyProvider::PLUGINS_USER_LOGIN_RESTRICTION,
            [$this->getUserLoginRestrictionPluginMock(false)],
        );
        $userTransfer = $this->tester->haveUser();

        // Act
        $user = $this->tester->getUser($userTransfer->getUsernameOrFail());

        // Assert
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    /**
     * @param bool $expectedResult
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface
     */
    protected function getUserLoginRestrictionPluginMock(bool $expectedResult): UserLoginRestrictionPluginInterface
    {
        $userLoginRestrictionPluginMock = $this->getMockBuilder(UserLoginRestrictionPluginInterface::class)->getMock();
        $userLoginRestrictionPluginMock->method('isRestricted')->willReturn($expectedResult);

        return $userLoginRestrictionPluginMock;
    }
}
