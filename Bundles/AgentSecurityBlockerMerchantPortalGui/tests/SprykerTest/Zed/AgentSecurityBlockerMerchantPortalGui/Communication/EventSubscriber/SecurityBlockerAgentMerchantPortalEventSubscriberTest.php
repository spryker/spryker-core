<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiConfig;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilder;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerAgentMerchantPortalEventSubscriber;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Client\AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface;
use SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiCommunicationTester;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityBlockerMerchantPortalGui
 * @group Communication
 * @group EventSubscriber
 * @group SecurityBlockerAgentMerchantPortalEventSubscriberTest
 * Add your own group annotations below this line
 */
class SecurityBlockerAgentMerchantPortalEventSubscriberTest extends Unit
{
    /**
     * @uses \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiConfig::SECURITY_BLOCKER_AGENT_MERCHANT_PORTAL_ENTITY_TYPE
     *
     * @var string
     */
    protected const AGENT_MERCHANT_PORTAL_TYPE = 'agent-merchant-portal';

    /**
     * @uses \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiConfig::AGENT_MERCHANT_PORTAL_LOGIN_CHECK_URL
     *
     * @var string
     */
    protected const LOGIN_CHECK_URL = 'agent-security-merchant-portal-gui_login_check';

    /**
     * @var \SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiCommunicationTester
     */
    protected AgentSecurityBlockerMerchantPortalGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerAgentMerchantPortalEventSubscriber
     */
    protected SecurityBlockerAgentMerchantPortalEventSubscriber $subscriber;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Client\AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    protected AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface $securityBlockerClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $requestStackMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requestStackMock = $this->createRequestStackMock();
        $this->securityBlockerClientMock = $this->createSecurityBlockerClientMock();
        $this->subscriber = $this->createSecurityBlockerAgentMerchantPortalEventSubscriber();
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventSubscriberShouldCallAgentSecurityBlockerClientOnKernalReqeustWhileSendingValidLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::AGENT_MERCHANT_PORTAL_TYPE);
        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(false);

        // Assert
        $this->securityBlockerClientMock->expects($this->once())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        // Act
        $event = $this->createRequestEventForMethod($securityCheckAuthContextTransfer, Request::METHOD_POST);
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($this->subscriber);
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventSubscriberShouldThrowHttpExceptionWhileSendingBlockedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::AGENT_MERCHANT_PORTAL_TYPE);
        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(true);

        // Assert
        $this->securityBlockerClientMock->expects($this->once())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);
        $this->expectException(HttpException::class);

        // Act
        $event = $this->createRequestEventForMethod($securityCheckAuthContextTransfer, Request::METHOD_POST);
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($this->subscriber);
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventSubscriberShouldNotCallAgentSecurityBlockerClientWhileSendingWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::AGENT_MERCHANT_PORTAL_TYPE);
        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())->setIsBlocked(false);

        // Assert
        $this->securityBlockerClientMock->expects($this->never())
            ->method('isAccountBlocked')
            ->with($securityCheckAuthContextTransfer)
            ->willReturn($securityCheckAuthResponseTransfer);

        // Act
        $event = $this->createRequestEventForMethod($securityCheckAuthContextTransfer, Request::METHOD_GET);
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($this->subscriber);
        $eventDispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventSubscriberShouldReturnAgentSecurityBlockerClientExceptionWhileFailedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::AGENT_MERCHANT_PORTAL_TYPE);

        // Assert
        $this->securityBlockerClientMock->expects($this->once())
            ->method('incrementLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->tester->createRequest(Request::METHOD_POST, $securityCheckAuthContextTransfer));

        // Act
        $event = $this->createRequestEventForMethod($securityCheckAuthContextTransfer, Request::METHOD_POST);
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($this->subscriber);
        $eventDispatcher->dispatch($event, LoginFailureEvent::class);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventSubscriberShouldNotCallAgentSecurityBlockerClientWhileSendingValidGetRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::AGENT_MERCHANT_PORTAL_TYPE);

        // Assert
        $this->securityBlockerClientMock->expects($this->never())
            ->method('incrementLoginAttemptCount')
            ->with($securityCheckAuthContextTransfer);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->tester->createRequest(Request::METHOD_GET, $securityCheckAuthContextTransfer));

        // Act
        $event = $this->createRequestEventForMethod($securityCheckAuthContextTransfer, Request::METHOD_POST);
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($this->subscriber);
        $eventDispatcher->dispatch($event, LoginFailureEvent::class);
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     * @param string $requestMethod
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function createRequestEventForMethod(
        SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer,
        string $requestMethod
    ): RequestEvent {
        $httpKernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = $this->tester->createRequest($requestMethod, $securityCheckAuthContextTransfer);

        return new RequestEvent($httpKernel, $request, HttpKernelInterface::MASTER_REQUEST);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStackMock(): RequestStack
    {
        return $this->createMock(RequestStack::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Client\AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    protected function createSecurityBlockerClientMock(): AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
    {
        return $this->createMock(AgentSecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface::class);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerAgentMerchantPortalEventSubscriber
     */
    protected function createSecurityBlockerAgentMerchantPortalEventSubscriber(): SecurityBlockerAgentMerchantPortalEventSubscriber
    {
        return new SecurityBlockerAgentMerchantPortalEventSubscriber(
            $this->requestStackMock,
            $this->securityBlockerClientMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->createConfiguredMock(
                AgentSecurityBlockerMerchantPortalGuiConfig::class,
                [
                    'getAgentMerchantPortalLoginCheckUrl' => static::LOGIN_CHECK_URL,
                    'getSecurityBlockerAgentMerchantPortalEntityType' => static::AGENT_MERCHANT_PORTAL_TYPE,
                ],
            ),
        );
    }
}
