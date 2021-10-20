<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Authorization;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Client\Authorization\AuthorizationDependencyProvider;
use Spryker\Client\Authorization\Exception\AuthorizationStrategyNotFoundException;
use Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Authorization
 * @group AuthorizationClientTest
 * Add your own group annotations below this line
 */
class AuthorizationClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Authorization\AuthorizationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAuthorizeSuccessful(): void
    {
        // Arrange
        $this->tester->setDependency(
            AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES,
            [$this->getAuthorizationStrategyPluginMock()],
        );
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setStrategy('test-strategy');

        // Act
        $authorizationResponseTransfer = $this->tester->getClient()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($authorizationResponseTransfer->getIsAuthorized());
    }

    /**
     * @return void
     */
    public function testAuthorizeUnsuccessful(): void
    {
        // Arrange
        $this->tester->setDependency(
            AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES,
            [$this->getAuthorizationStrategyPluginMock(false)],
        );
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setStrategy('test-strategy');

        // Act
        $authorizationResponseTransfer = $this->tester->getClient()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($authorizationResponseTransfer->getIsAuthorized());
    }

    /**
     * @return void
     */
    public function testAuthorizeException(): void
    {
        // Arrange
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setStrategy('test-strategy');

        // Assert
        $this->expectException(AuthorizationStrategyNotFoundException::class);

        // Act
        $this->tester->getClient()->authorize($authorizationRequestTransfer);
    }

    /**
     * @param bool $isAuthorized
     *
     * @return \Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface
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
