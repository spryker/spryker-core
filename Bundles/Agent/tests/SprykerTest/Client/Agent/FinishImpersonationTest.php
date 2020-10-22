<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Agent;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Agent\AgentDependencyProvider;
use Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Agent
 * @group FinishImpersonationTest
 * Add your own group annotations below this line
 */
class FinishImpersonationTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Agent\AgentClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFinishImpersonationSupportsImpersonationFinisherPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            AgentDependencyProvider::PLUGINS_IMPERSONATION_FINISHER,
            [$this->getImpersonationFinisherPluginMock()]
        );

        // Act
        $this->tester
            ->getClient()
            ->finishImpersonation(new CustomerTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface
     */
    protected function getImpersonationFinisherPluginMock(): ImpersonationFinisherPluginInterface
    {
        $customerImpersonationSanitizerPluginMock = $this
            ->getMockBuilder(ImpersonationFinisherPluginInterface::class)
            ->getMock();

        $customerImpersonationSanitizerPluginMock
            ->expects($this->once())
            ->method('finish');

        return $customerImpersonationSanitizerPluginMock;
    }
}
