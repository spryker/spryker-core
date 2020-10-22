<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Agent;

use Codeception\Test\Unit;
use Spryker\Client\Agent\AgentDependencyProvider;
use Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface;
use Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Agent
 * @group FinishImpersonationSessionTest
 * Add your own group annotations below this line
 */
class FinishImpersonationSessionTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Agent\AgentClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFinishImpersonationSessionSupportsImpersonationSessionFinisherPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            AgentDependencyProvider::PLUGINS_IMPERSONATION_SESSION_FINISHER,
            [$this->getImpersonationSessionFinisherPluginMock()]
        );

        // Act
        $this->tester
            ->getClient()
            ->finishImpersonationSession();
    }

    /**
     * @return void
     */
    public function testFinishImpersonationEnsureCustomerLogoutIsExecuted(): void
    {
        // Arrange
        $this->tester->setDependency(
            AgentDependencyProvider::CLIENT_CUSTOMER,
            $this->getCustomerClientMock()
        );

        // Act
        $this->tester
            ->getClient()
            ->finishImpersonationSession();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface
     */
    protected function getImpersonationSessionFinisherPluginMock(): ImpersonationSessionFinisherPluginInterface
    {
        $customerImpersonationSessionSanitizerPluginMock = $this
            ->getMockBuilder(ImpersonationSessionFinisherPluginInterface::class)
            ->getMock();

        $customerImpersonationSessionSanitizerPluginMock
            ->expects($this->once())
            ->method('finish');

        return $customerImpersonationSessionSanitizerPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Agent\Dependency\Client\AgentToCustomerClientInterface
     */
    protected function getCustomerClientMock(): AgentToCustomerClientInterface
    {
        $customerClientMock = $this
            ->getMockBuilder(AgentToCustomerClientInterface::class)
            ->getMock();

        $customerClientMock
            ->expects($this->once())
            ->method('logout');

        return $customerClientMock;
    }
}
