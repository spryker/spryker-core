<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\AuditLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group AbstractPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractPluginTest extends Unit
{
    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAgentSecurityMerchantPortalGuiCommunicationFactoryMock(
        string $expectedMessage
    ): AgentSecurityMerchantPortalGuiCommunicationFactory {
        $auditLoggerMock = $this->getAuditLoggerMock($expectedMessage);
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock = $this->getMockBuilder(AgentSecurityMerchantPortalGuiCommunicationFactory::class)
            ->getMock();
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock->method('createAuditLogger')->willReturn($auditLoggerMock);

        return $agentSecurityMerchantPortalGuiCommunicationFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuditLoggerMock(string $expectedMessage): AuditLogger
    {
        $auditLoggerMock = $this->getMockBuilder(AuditLogger::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAuditLogger', 'addOriginalUserContext'])
            ->getMock();
        $auditLoggerMock->expects($this->once())
            ->method('getAuditLogger')
            ->willReturn($this->getLoggerMock($expectedMessage));
        $auditLoggerMock->method('addOriginalUserContext')->willReturn([]);

        return $auditLoggerMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getLoggerMock(string $expectedMessage): LoggerInterface
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loggerMock->expects($this->once())->method('info')->with($expectedMessage);

        return $loggerMock;
    }
}
