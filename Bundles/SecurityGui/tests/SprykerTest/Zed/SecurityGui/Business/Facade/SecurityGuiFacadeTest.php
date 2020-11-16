<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeBridge;
use Spryker\Zed\SecurityGui\SecurityGuiDependencyProvider;
use Spryker\Zed\User\Business\UserFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
 * @group Business
 * @group Facade
 * @group Facade
 * @group SecurityGuiFacadeTest
 * Add your own group annotations below this line
 */
class SecurityGuiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SecurityGui\SecurityGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAuthenticateUserCallsUserFacade(): void
    {
        // Arrange
        $userFacadeMock = $this->createUserFacadeMock();

        // Assert
        $userFacadeMock->expects($this->once())
            ->method('setCurrentUser');
        $userFacadeMock->expects($this->once())
            ->method('updateUser');

        $userTransfer = $this->tester->haveUser();

        // Act
        $this->tester
            ->getSecurityGuiFacade()
            ->authenticateUser($userTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function createUserFacadeMock(): UserFacadeInterface
    {
        /** @var \Spryker\Zed\User\Business\UserFacadeInterface $userFacadeMock */
        $userFacadeMock = $this->getMockBuilder(UserFacadeInterface::class)->getMock();

        $this->tester->setDependency(
            SecurityGuiDependencyProvider::FACADE_USER,
            new SecurityGuiToUserFacadeBridge($userFacadeMock)
        );

        return $userFacadeMock;
    }
}
