<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AgentAuthRestApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiFactory;
use Spryker\Glue\AgentAuthRestApi\Controller\AgentAccessTokensResourceController;
use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreator;
use Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreatorInterface;
use Spryker\Glue\AgentAuthRestApi\Processor\Logger\AuditLogger;
use Spryker\Glue\AgentAuthRestApi\Processor\RestResponseBuilder\AgentAccessTokenRestResponseBuilderInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group AgentAuthRestApi
 * @group Controller
 * @group AgentAccessTokensResourceControllerTest
 * Add your own group annotations below this line
 */
class AgentAccessTokensResourceControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testPostActionAddsAgentFailedLoginAuditLogWhenLoginAttemptFails(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restAgentAccessTokensRequestAttributesTransfer = new RestAgentAccessTokensRequestAttributesTransfer();
        $agentAccessTokensResourceControllerMock = $this->getAgentAccessTokensResourceControllerMock('Failed Login (Agent)', false);

        // Act
        $agentAccessTokensResourceControllerMock->postAction($restRequest, $restAgentAccessTokensRequestAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testPostActionAddsAgentSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restAgentAccessTokensRequestAttributesTransfer = new RestAgentAccessTokensRequestAttributesTransfer();
        $agentAccessTokensResourceControllerMock = $this->getAgentAccessTokensResourceControllerMock('Successful Login (Agent)', true);

        // Act
        $agentAccessTokensResourceControllerMock->postAction($restRequest, $restAgentAccessTokensRequestAttributesTransfer);
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AgentAuthRestApi\Controller\AgentAccessTokensResourceController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAgentAccessTokensResourceControllerMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): AgentAccessTokensResourceController {
        $agentAuthRestApiFactoryMock = $this->getMockBuilder(AgentAuthRestApiFactory::class)->getMock();
        $agentAuthRestApiFactoryMock->method('createAgentAccessTokenCreator')->willReturn(
            $this->getAgentAccessTokenCreatorMock($expectedMessage, $isValidOauthResponse),
        );
        $agentAccessTokensResourceControllerMock = $this->getMockBuilder(AgentAccessTokensResourceController::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $agentAccessTokensResourceControllerMock->method('getFactory')->willReturn($agentAuthRestApiFactoryMock);

        return $agentAccessTokensResourceControllerMock;
    }

    /**
     * @param string $expectedMessage
     * @param bool $isValidOauthResponse
     *
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Creator\AgentAccessTokenCreatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAgentAccessTokenCreatorMock(
        string $expectedMessage,
        bool $isValidOauthResponse
    ): AgentAccessTokenCreatorInterface {
        return $this->getMockBuilder(AgentAccessTokenCreator::class)
            ->onlyMethods([])
            ->setConstructorArgs([
                $this->getOauthClientMock($isValidOauthResponse),
                $this->createMock(AgentAccessTokenRestResponseBuilderInterface::class),
                $this->getAuditLoggerMock($expectedMessage),
            ])->getMock();
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Glue\AgentAuthRestApi\Processor\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
     * @return \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOauthClientMock(bool $isValidOauthResponse): AgentAuthRestApiToOauthClientInterface
    {
        $oauthClientMock = $this->getMockBuilder(AgentAuthRestApiToOauthClientInterface::class)->getMock();
        $oauthClientMock->method('processAccessTokenRequest')->willReturn((new OauthResponseTransfer())->setIsValid($isValidOauthResponse));

        return $oauthClientMock;
    }
}
