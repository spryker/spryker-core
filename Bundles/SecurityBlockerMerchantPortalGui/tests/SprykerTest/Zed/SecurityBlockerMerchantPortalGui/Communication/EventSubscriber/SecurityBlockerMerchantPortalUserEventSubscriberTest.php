<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilder;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig;
use SprykerTest\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiCommunicationTester;
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
 * @group SecurityBlockerMerchantPortalGui
 * @group Communication
 * @group EventSubscriber
 * @group SecurityBlockerMerchantPortalUserEventSubscriberTest
 * Add your own group annotations below this line
 */
class SecurityBlockerMerchantPortalUserEventSubscriberTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig::SECURITY_BLOCKER_MERCHANT_PORTAL_USER_ENTITY_TYPE
     *
     * @var string
     */
    protected const MERCHANT_PORTAL_USER_TYPE = 'merchant-portal-user';

    /**
     * @uses \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig::MERCHANT_PORTAL_LOGIN_CHECK_URL
     *
     * @var string
     */
    protected const LOGIN_CHECK_URL = '/security-merchant-portal-gui/login_check';

    /**
     * @var \SprykerTest\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiCommunicationTester
     */
    protected SecurityBlockerMerchantPortalGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber
     */
    protected SecurityBlockerMerchantPortalUserEventSubscriber $subscriber;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    protected SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface $securityBlockerClientMock;

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
        $this->subscriber = $this->createSecurityBlockerMerchantPortalUserEventSubscriber();
    }

    /**
     * @return void
     */
    public function testSecurityBlockerMerchantPortalUserEventSubscriberShouldCallSecurityBlockerClientOnKernalReqeustWhileSendingValidLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::MERCHANT_PORTAL_USER_TYPE);
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
    public function testSecurityBlockerMerchantPortalUserEventSubscriberShouldThrowHttpExceptionWhileSendingBlockedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::MERCHANT_PORTAL_USER_TYPE);
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
    public function testSecurityBlockerMerchantPortalUserEventSubscriberShouldNotCallSecurityBlockerClientWhileSendingWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::MERCHANT_PORTAL_USER_TYPE);
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
    public function testSecurityBlockerMerchantPortalUserEventSubscriberShouldReturnSecurityBlockerClientExceptionWhileFailedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::MERCHANT_PORTAL_USER_TYPE);

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
    public function testSecurityBlockerMerchantPortalUserEventSubscriberShouldNotCallSecurityBlockerClientWhileSendingValidGetRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::MERCHANT_PORTAL_USER_TYPE);

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    protected function createSecurityBlockerClientMock(): SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
    {
        return $this->createMock(SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface::class);
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber\SecurityBlockerMerchantPortalUserEventSubscriber
     */
    protected function createSecurityBlockerMerchantPortalUserEventSubscriber(): SecurityBlockerMerchantPortalUserEventSubscriber
    {
        return new SecurityBlockerMerchantPortalUserEventSubscriber(
            $this->requestStackMock,
            $this->securityBlockerClientMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->createConfiguredMock(
                SecurityBlockerMerchantPortalGuiConfig::class,
                [
                    'getMerchantPortalUserLoginCheckUrl' => static::LOGIN_CHECK_URL,
                    'getSecurityBlockerMerchantPortalUserEntityType' => static::MERCHANT_PORTAL_USER_TYPE,
                ],
            ),
        );
    }
}
