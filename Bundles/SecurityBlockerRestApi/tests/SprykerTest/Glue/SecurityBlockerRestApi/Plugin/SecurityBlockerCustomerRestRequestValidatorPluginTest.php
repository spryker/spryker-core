<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SecurityBlockerRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Plugin\GlueApplication\SecurityBlockerCustomerRestRequestValidatorPlugin;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group SecurityBlockerRestApi
 * @group Plugin
 * @group SecurityBlockerCustomerRestRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class SecurityBlockerCustomerRestRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Plugin\GlueApplication\SecurityBlockerCustomerRestRequestValidatorPlugin
     */
    protected $securityBlockerCustomerRestRequestValidatorPlugin;

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
            ->getSecurityCheckAuthContextTransfer(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerCustomerRestRequestValidatorPluginWillCallSecurityBlockerClient(): void
    {
        // Arrange
        $securityBlockerClientMock = $this->getMockBuilder(SecurityBlockerRestApiToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerClientMock->expects($this->once())
            ->method('getLoginAttemptCount')
            ->with($this->securityCheckAuthContextTransfer)
            ->willReturn((new SecurityCheckAuthResponseTransfer())->setIsBlocked(true));

        $securityBlockerRestApiFactoryMock = $this->getMockBuilder(SecurityBlockerRestApiFactory::class)->onlyMethods(['getSecurityBlockerClient', 'getGlossaryStorageClient'])->getMock();
        $securityBlockerRestApiFactoryMock->method('getSecurityBlockerClient')->willReturn($securityBlockerClientMock);

        $this->securityBlockerCustomerRestRequestValidatorPlugin = new SecurityBlockerCustomerRestRequestValidatorPlugin();
        $this->securityBlockerCustomerRestRequestValidatorPlugin->setFactory($securityBlockerRestApiFactoryMock);

        $restResourceMock = $this->getRestResourceMock($this->securityCheckAuthContextTransfer);
        $restRequestMock = $this->getRestRequestMock($restResourceMock, $this->securityCheckAuthContextTransfer);

        // Act
        $this->securityBlockerCustomerRestRequestValidatorPlugin->validate(
            $restRequestMock->getHttpRequest(),
            $restRequestMock
        );
    }

    /**
     * @return void
     */
    public function testSecurityBlockerCustomerRestRequestValidatorPluginWillNotCallSecurityBlockerClientOnInvalidRequest(): void
    {
        // Arrange
        $securityBlockerClientMock = $this->getMockBuilder(SecurityBlockerRestApiToSecurityBlockerClientInterface::class)->getMock();
        $securityBlockerClientMock->expects($this->never())
            ->method('incrementLoginAttemptCount')
            ->with($this->securityCheckAuthContextTransfer);

        $securityBlockerRestApiFactoryMock = $this->getMockBuilder(SecurityBlockerRestApiFactory::class)->onlyMethods(['getSecurityBlockerClient', 'getGlossaryStorageClient'])->getMock();
        $securityBlockerRestApiFactoryMock->method('getSecurityBlockerClient')->willReturn($securityBlockerClientMock);

        $this->securityBlockerCustomerRestRequestValidatorPlugin = new SecurityBlockerCustomerRestRequestValidatorPlugin();
        $this->securityBlockerCustomerRestRequestValidatorPlugin->setFactory($securityBlockerRestApiFactoryMock);

        $restResourceMock = $this->getRestResourceMock($this->securityCheckAuthContextTransfer);
        $restRequestMock = $this->getInvalidRestRequestMock($restResourceMock, $this->securityCheckAuthContextTransfer);

        // Act
        $this->securityBlockerCustomerRestRequestValidatorPlugin->validate(
            $restRequestMock->getHttpRequest(),
            $restRequestMock
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
        $restResourceMock->method('getType')->willReturn(SecurityBlockerRestApiConfig::RESOURCE_ACCESS_TOKENS);
        $restResourceMock->method('getAttributes')->willReturn($this->tester->getRestAccessTokensAttributesTransfer($securityCheckAuthContextTransfer));

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
}
