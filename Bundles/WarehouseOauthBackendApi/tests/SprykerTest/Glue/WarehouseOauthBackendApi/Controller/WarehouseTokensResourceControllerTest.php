<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\WarehouseOauthBackendApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Controller\WarehouseTokensResourceController;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeBridge;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator\WarehouseTokenCreator;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLogger;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReader;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilder;
use Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group WarehouseOauthBackendApi
 * @group Controller
 * @group WarehouseTokensResourceControllerTest
 * Add your own group annotations below this line
 */
class WarehouseTokensResourceControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testPostActionAddsWarehouseUserFailedLoginAuditLogWhenActiveWarehouseUserNorFound(): void
    {
        // Arrange
        $warehouseTokensResourceControllerMock = $this->getWarehouseTokensResourceControllerMock(
            'Failed Login (Warehouse User)',
            false,
            true,
        );
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(new GlueRequestUserTransfer());

        // Act
        $warehouseTokensResourceControllerMock->postAction($glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testPostActionAddsWarehouseUserFailedLoginAuditLogWhenOauthResponseIsNotValid(): void
    {
        // Arrange
        $warehouseTokensResourceControllerMock = $this->getWarehouseTokensResourceControllerMock(
            'Failed Login (Warehouse User)',
            true,
            false,
        );
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(new GlueRequestUserTransfer());

        // Act
        $warehouseTokensResourceControllerMock->postAction($glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testPostActionAddsWarehouseUserSuccessfulLoginAuditLog(): void
    {
        // Arrange
        $warehouseTokensResourceControllerMock = $this->getWarehouseTokensResourceControllerMock(
            'Successful Login (Warehouse User)',
            true,
            true,
        );
        $glueRequestTransfer = (new GlueRequestTransfer())->setRequestUser(new GlueRequestUserTransfer());

        // Act
        $warehouseTokensResourceControllerMock->postAction($glueRequestTransfer);
    }

    /**
     * @param string $expectedMessage
     * @param bool $isWarehouseUserAssignmentFound
     * @param bool $isOauthResponseValid
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Controller\WarehouseTokensResourceController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getWarehouseTokensResourceControllerMock(
        string $expectedMessage,
        bool $isWarehouseUserAssignmentFound,
        bool $isOauthResponseValid
    ): WarehouseTokensResourceController {
        $warehouseTokensResourceControllerMock = $this->getMockBuilder(WarehouseTokensResourceController::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $warehouseTokensResourceControllerMock->method('getFactory')
            ->willReturn($this->getWarehouseOauthBackendApiFactoryFactoryMock($expectedMessage, $isWarehouseUserAssignmentFound, $isOauthResponseValid));

        return $warehouseTokensResourceControllerMock;
    }

    /**
     * @param string $expectedMessage
     * @param bool $isWarehouseUserAssignmentFound
     * @param bool $isOauthResponseValid
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getWarehouseOauthBackendApiFactoryFactoryMock(
        string $expectedMessage,
        bool $isWarehouseUserAssignmentFound,
        bool $isOauthResponseValid
    ): WarehouseOauthBackendApiFactory {
        $warehouseOauthBackendApiFactoryFactoryMock = $this->createMock(WarehouseOauthBackendApiFactory::class);
        $warehouseOauthBackendApiFactoryFactoryMock->method('createWarehouseTokenCreator')
            ->willReturn($this->getWarehouseTokenCreatorMock($expectedMessage, $isWarehouseUserAssignmentFound, $isOauthResponseValid));

        return $warehouseOauthBackendApiFactoryFactoryMock;
    }

    /**
     * @param string $expectedMessage
     * @param bool $isWarehouseUserAssignmentFound
     * @param bool $isOauthResponseValid
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator\WarehouseTokenCreator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getWarehouseTokenCreatorMock(
        string $expectedMessage,
        bool $isWarehouseUserAssignmentFound,
        bool $isOauthResponseValid
    ): WarehouseTokenCreator {
        return $this->getMockBuilder(WarehouseTokenCreator::class)
            ->onlyMethods([])
            ->setConstructorArgs([
                $this->getWarehouseUserAssignmentReaderMock($isWarehouseUserAssignmentFound),
                $this->getAuthenticationFacadeMock($isOauthResponseValid),
                $this->createMock(WarehouseResponseBuilder::class),
                $this->getAuditLoggerMock($expectedMessage),
            ])
            ->getMock();
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('info')->with($expectedMessage);

        return $loggerMock;
    }

    /**
     * @param bool $isWarehouseUserAssignmentFound
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getWarehouseUserAssignmentReaderMock(
        bool $isWarehouseUserAssignmentFound
    ): WarehouseUserAssignmentReader {
        $warehouseUserAssignmentTransfer = $isWarehouseUserAssignmentFound
            ? (new WarehouseUserAssignmentTransfer())->setWarehouse((new StockTransfer())->setIdStock(1))
            : null;
        $warehouseUserAssignmentReaderMock = $this->createMock(WarehouseUserAssignmentReader::class);
        $warehouseUserAssignmentReaderMock->method('findActiveWarehouseUserAssignment')
            ->willReturn($warehouseUserAssignmentTransfer);

        return $warehouseUserAssignmentReaderMock;
    }

    /**
     * @param bool $isOauthResponseValid
     *
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuthenticationFacadeMock(
        bool $isOauthResponseValid
    ): WarehouseOauthBackendApiToAuthenticationFacadeBridge {
        $warehouseOauthBackendApiToAuthenticationFacadeBridge = $this->createMock(
            WarehouseOauthBackendApiToAuthenticationFacadeBridge::class,
        );
        $warehouseOauthBackendApiToAuthenticationFacadeBridge->method('authenticate')
            ->willReturn((new GlueAuthenticationResponseTransfer())->setOauthResponse(
                (new OauthResponseTransfer())->setIsValid($isOauthResponseValid)->setError(new OauthErrorTransfer()),
            ));

        return $warehouseOauthBackendApiToAuthenticationFacadeBridge;
    }
}
