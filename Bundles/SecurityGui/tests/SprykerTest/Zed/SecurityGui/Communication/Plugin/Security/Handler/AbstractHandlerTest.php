<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Plugin\Security\Handler;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SecurityGui\Communication\Logger\AuditLogger;
use Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
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
     * @return \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityGuiCommunicationFactoryMock(string $expectedAuditLogMessage): SecurityGuiCommunicationFactory
    {
        $securityGuiCommunicationFactoryMock = $this->getMockBuilder(SecurityGuiCommunicationFactory::class)
            ->getMock();
        $securityGuiCommunicationFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));

        return $securityGuiCommunicationFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\SecurityGui\Communication\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
}
