<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AuthRestApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Client\AuthRestApi\AuthRestApiClient;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReader;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthToken;
use Spryker\Glue\AuthRestApi\Processor\Logger\AuditLogger;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AuthRestApi
 * @group Controller
 * @group AbstractControllerTest
 * Add your own group annotations below this line
 */
abstract class AbstractControllerTest extends Unit
{
    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAccessTokensReaderMock(string $expectedMessage, bool $isValidOauthResponse): AccessTokensReader
    {
        return $this->getMockBuilder(AccessTokensReader::class)
            ->onlyMethods([])
            ->setConstructorArgs([
                $this->getAuthRestApiClientMock($isValidOauthResponse),
                new RestResourceBuilder(),
                new AuthRestApiConfig(),
                $this->getAuditLoggerMock($expectedMessage),
            ])
            ->getMock();
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOauthTokenMock(string $expectedMessage, bool $isValidOauthResponse): OauthToken
    {
        return $this->getMockBuilder(OauthToken::class)
            ->onlyMethods([])
            ->setConstructorArgs([
                $this->getAuthRestApiClientMock($isValidOauthResponse),
                $this->getAuditLoggerMock($expectedMessage),
            ])
            ->getMock();
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Glue\AuthRestApi\Processor\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Client\AuthRestApi\AuthRestApiClient|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuthRestApiClientMock(bool $isValidOauthResponse): AuthRestApiClient
    {
        $authRestApiClientMock = $this->getMockBuilder(AuthRestApiClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createAccessToken'])
            ->getMock();
        $authRestApiClientMock->method('createAccessToken')->willReturn(
            (new OauthResponseTransfer())->setIsValid($isValidOauthResponse)->setError(new OauthErrorTransfer()),
        );

        return $authRestApiClientMock;
    }
}
