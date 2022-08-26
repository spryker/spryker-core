<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Authorization\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;
use Spryker\Zed\Authorization\AuthorizationDependencyProvider;
use Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Authorization
 * @group Business
 * @group Facade
 * @group AuthorizationFacadeTest
 * Add your own group annotations below this line
 */
class AuthorizationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Authorization\AuthorizationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAuthorizeWithAuthorizationStrategiesSuccessful(): void
    {
        // Arrange
        $this->tester->setDependency(
            AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES,
            [$this->getAuthorizationStrategyPluginMock()],
        );
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->addStrategy('test-strategy');

        // Act
        $authorizationResponseTransfer = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($authorizationResponseTransfer->getIsAuthorized());
    }

    /**
     * @return void
     */
    public function testAuthorizeWithAuthorizationStrategiesUnsuccessful(): void
    {
        // Arrange
        $this->tester->setDependency(
            AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES,
            [$this->getAuthorizationStrategyPluginMock(false)],
        );
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->addStrategy('test-strategy');

        // Act
        $authorizationResponseTransfer = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($authorizationResponseTransfer->getIsAuthorized());
    }

    /**
     * @return void
     */
    public function testAuthorizeWithAuthorizationStrategiesException(): void
    {
        // Arrange
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->addStrategy('test-strategy');

        // Assert
        $this->expectException(AuthorizationStrategyNotFoundException::class);

        // Act
        $this->tester->getFacade()->authorize($authorizationRequestTransfer);
    }

    /**
     * @param bool $isAuthorized
     *
     * @return \Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface
     */
    protected function getAuthorizationStrategyPluginMock(bool $isAuthorized = true): AuthorizationStrategyPluginInterface
    {
        $authorizationStrategyPluginMock = $this->getMockBuilder(AuthorizationStrategyPluginInterface::class)->getMock();

        $authorizationStrategyPluginMock
            ->method('authorize')
            ->willReturn($isAuthorized);

        $authorizationStrategyPluginMock
            ->method('getStrategyName')
            ->willReturn('test-strategy');

        return $authorizationStrategyPluginMock;
    }
}
