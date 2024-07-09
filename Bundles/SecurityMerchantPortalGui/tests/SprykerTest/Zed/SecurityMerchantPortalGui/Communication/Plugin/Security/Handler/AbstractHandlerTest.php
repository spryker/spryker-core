<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger\AuditLogger;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Handler
 * @group AbstractHandlerTest
 * Add your own group annotations below this line
 */
abstract class AbstractHandlerTest extends Unit
{
    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityMerchantPortalGuiCommunicationFactoryMock(
        string $expectedAuditLogMessage
    ): SecurityMerchantPortalGuiCommunicationFactory {
        $securityMerchantPortalGuiCommunicationFactoryMock = $this->getMockBuilder(SecurityMerchantPortalGuiCommunicationFactory::class)
            ->getMock();
        $securityMerchantPortalGuiCommunicationFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $securityMerchantPortalGuiCommunicationFactoryMock->method('getMerchantUserFacade')
            ->willReturn($this->getMerchantUserFacadeMock());

        return $securityMerchantPortalGuiCommunicationFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuditLoggerMock(string $expectedMessage): AuditLogger
    {
        $auditLoggerMock = $this->getMockBuilder(AuditLogger::class)
            ->onlyMethods(['getAuditLogger'])
            ->getMock();
        $auditLoggerMock->expects($this->once())
            ->method('getAuditLogger')
            ->willReturn($this->getLoggerMock($expectedMessage));

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

    /**
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMerchantUserFacadeMock(): SecurityMerchantPortalGuiToMerchantUserFacadeBridge
    {
        return $this->createMock(SecurityMerchantPortalGuiToMerchantUserFacadeBridge::class);
    }
}
