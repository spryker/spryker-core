<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SecurityBlockerRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Plugin\GlueApplication\SecurityBlockerAgentControllerAfterActionPlugin;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group SecurityBlockerRestApi
 * @group Plugin
 * @group SecurityBlockerAgentControllerAfterActionPluginTest
 * Add your own group annotations below this line
 */
class SecurityBlockerAgentControllerAfterActionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Plugin\GlueApplication\SecurityBlockerAgentControllerAfterActionPlugin
     */
    protected $securityBlockerAgentControllerAfterActionPlugin;

    /**
     * @var \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    protected $securityCheckAuthContextTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->securityCheckAuthContextTransfer = $this->tester
            ->getSecurityCheckAuthContextTransfer(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentControllerAfterActionPluginWillCallSecurityBlockerClient(): void
    {
        // Arrange
        $securityBlockerClientMock = $this->getMockBuilder(SecurityBlockerRestApiToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerClientMock->expects($this->once())
            ->method('incrementLoginAttemptCount')
            ->with($this->securityCheckAuthContextTransfer);

        $securityBlockerRestApiFactoryMock = $this->getMockBuilder(SecurityBlockerRestApiFactory::class)->onlyMethods(['getSecurityBlockerClient'])->getMock();
        $securityBlockerRestApiFactoryMock->method('getSecurityBlockerClient')->willReturn($securityBlockerClientMock);

        $this->securityBlockerAgentControllerAfterActionPlugin = new SecurityBlockerAgentControllerAfterActionPlugin();
        $this->securityBlockerAgentControllerAfterActionPlugin->setFactory($securityBlockerRestApiFactoryMock);

        $restResourceMock = $this->getRestResourceMock($this->securityCheckAuthContextTransfer);
        $restRequestMock = $this->getRestRequestMock($restResourceMock, $this->securityCheckAuthContextTransfer);
        $restResponseMock = $this->getRestResponseMock();

        // Act
        $this->securityBlockerAgentControllerAfterActionPlugin->afterAction(
            SecurityBlockerRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS,
            $restRequestMock,
            $restResponseMock
        );
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentControllerAfterActionPluginWillNotCallSecurityBlockerClientOnInvalidRequest(): void
    {
        // Arrange
        $securityBlockerClientMock = $this->getMockBuilder(SecurityBlockerRestApiToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerClientMock->expects($this->never())
            ->method('incrementLoginAttemptCount')
            ->with($this->securityCheckAuthContextTransfer);

        $securityBlockerRestApiFactoryMock = $this->getMockBuilder(SecurityBlockerRestApiFactory::class)->onlyMethods(['getSecurityBlockerClient'])->getMock();
        $securityBlockerRestApiFactoryMock->method('getSecurityBlockerClient')->willReturn($securityBlockerClientMock);

        $this->securityBlockerAgentControllerAfterActionPlugin = new SecurityBlockerAgentControllerAfterActionPlugin();
        $this->securityBlockerAgentControllerAfterActionPlugin->setFactory($securityBlockerRestApiFactoryMock);

        $restResourceMock = $this->getRestResourceMock($this->securityCheckAuthContextTransfer);
        $restRequestMock = $this->getInvalidRestRequestMock($restResourceMock, $this->securityCheckAuthContextTransfer);
        $restResponseMock = $this->getRestResponseMock();

        // Act
        $this->securityBlockerAgentControllerAfterActionPlugin->afterAction(
            SecurityBlockerRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS,
            $restRequestMock,
            $restResponseMock
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getRestResourceMock(
        SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
    ): RestResourceInterface {
        $restResourceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $restResourceMock->method('getType')->willReturn(SecurityBlockerRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS);
        $restResourceMock->method('getAttributes')->willReturn($this->tester->getRestAgentAccessTokensRequestAttributesTransfer($securityCheckAuthContextTransfer));

        return $restResourceMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResourceMock
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getRestRequestMock(
        RestResourceInterface $restResourceMock,
        SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
    ): RestRequestInterface {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_POST);
        $requestMock->method('getClientIp')->willReturn($securityCheckAuthContextTransfer->getIp());

        $restRequestMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $restRequestMock->method('getResource')->willReturn($restResourceMock);
        $restRequestMock->method('getHttpRequest')->willReturn($requestMock);

        return $restRequestMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResourceMock
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getInvalidRestRequestMock(
        RestResourceInterface $restResourceMock,
        SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
    ): RestRequestInterface {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_GET);
        $requestMock->method('getClientIp')->willReturn($securityCheckAuthContextTransfer->getIp());

        $restRequestMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $restRequestMock->method('getResource')->willReturn($restResourceMock);
        $restRequestMock->method('getHttpRequest')->willReturn($requestMock);

        return $restRequestMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestResponseMock(): RestResponseInterface
    {
        $restResponseMock = $this->getMockBuilder(RestResponseInterface::class)->disableOriginalConstructor()->getMock();
        $restResponseMock->method('getStatus')->willReturn(Response::HTTP_UNAUTHORIZED);
        $restResponseMock->method('getErrors')->willReturn([$this->tester->getAgentRestErrorMessageTransfer()]);

        return $restResponseMock;
    }
}
