<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Agent;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Agent\AgentDependencyProvider;
use Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Agent
 * @group SanitizeCustomerImpersonationTest
 * Add your own group annotations below this line
 */
class SanitizeCustomerImpersonationTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Agent\AgentClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSanitizeCustomerImpersonationSupportsCustomerImpersonationSanitizerPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            AgentDependencyProvider::PLUGINS_CUSTOMER_IMPERSONATION_SANITIZER,
            [$this->getCustomerImpersonationSanitizerPluginMock()]
        );

        $customerTransfer = new CustomerTransfer();

        // Act
        $this->tester
            ->getClient()
            ->sanitizeCustomerImpersonation($customerTransfer);

        // Assert
        $this->assertNotNull($customerTransfer->getUpdatedAt());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface
     */
    protected function getCustomerImpersonationSanitizerPluginMock(): CustomerImpersonationSanitizerPluginInterface
    {
        $customerImpersonationSanitizerPluginMock = $this
            ->getMockBuilder(CustomerImpersonationSanitizerPluginInterface::class)
            ->getMock();

        $customerImpersonationSanitizerPluginMock
            ->method('sanitize')
            ->willReturnCallback(function (CustomerTransfer $customerTransfer) {
                $customerTransfer->setUpdatedAt(new DateTime());
            });

        return $customerImpersonationSanitizerPluginMock;
    }
}
