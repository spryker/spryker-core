<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthBackendApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Glue\OauthBackendApi\Controller\TokenResourceController;
use Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToAuthenticationFacadeBridge;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;
use Spryker\Glue\OauthBackendApi\OauthBackendApiFactory;
use Spryker\Glue\OauthBackendApi\Processor\Logger\AuditLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthBackendApi
 * @group Controller
 * @group TokenResourceControllerTest
 * Add your own group annotations below this line
 */
class TokenResourceControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testPostActionAddsUnsuccessfulLoginAuditLogWhenLoginAttemptUnsuccessful(): void
    {
        // Arrange
        $tokenResourceControllerMock = $this->getTokenResourceControllerMock('Failed Login', false);

        // Act
        $tokenResourceControllerMock->postAction(new ApiTokenAttributesTransfer(), new GlueRequestTransfer());
    }

    /**
     * @return void
     */
    public function testPostActionAddsSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $tokenResourceControllerMock = $this->getTokenResourceControllerMock('Successful Login', true);

        // Act
        $tokenResourceControllerMock->postAction(new ApiTokenAttributesTransfer(), new GlueRequestTransfer());
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\OauthBackendApi\Controller\TokenResourceController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getTokenResourceControllerMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): TokenResourceController {
        $tokenResourceControllerMock = $this->getMockBuilder(TokenResourceController::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $tokenResourceControllerMock->method('getFactory')->willReturn(
            $this->getOauthBackendApiFactoryMock($expectedMessage, $isValidOauthResponse),
        );

        return $tokenResourceControllerMock;
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\OauthBackendApi\OauthBackendApiFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOauthBackendApiFactoryMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): OauthBackendApiFactory {
        $oauthBackendApiFactoryMock = $this->createMock(OauthBackendApiFactory::class);
        $oauthBackendApiFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedMessage));
        $oauthBackendApiFactoryMock->method('getAuthenticationFacade')
            ->willReturn($this->getAuthenticationFacadeMock($isValidOauthResponse));
        $oauthBackendApiFactoryMock->method('getConfig')->willReturn($this->getOauthBackendApiConfigMock());

        return $oauthBackendApiFactoryMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Glue\OauthBackendApi\Processor\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToAuthenticationFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuthenticationFacadeMock(bool $isValidOauthResponse): OauthBackendApiToAuthenticationFacadeBridge
    {
        $authenticationFacadeMock = $this->createMock(OauthBackendApiToAuthenticationFacadeBridge::class);
        $authenticationFacadeMock->method('authenticate')->willReturn(
            (new GlueAuthenticationResponseTransfer())->setOauthResponse(
                (new OauthResponseTransfer())
                    ->setIsValid($isValidOauthResponse)
                    ->setError(new OauthErrorTransfer()),
            ),
        );

        return $authenticationFacadeMock;
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\OauthBackendApiConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOauthBackendApiConfigMock(): OauthBackendApiConfig
    {
        return $this->createMock(OauthBackendApiConfig::class);
    }
}
