<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityBlockerBackofficeGui\Communication\EventSubscriber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Builder\MessageBuilder;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\EventSubscriber\SecurityBlockerBackofficeUserEventSubscriber;
use Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Client\SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface;
use Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiConfig;
use SprykerTest\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiCommunicationTester;
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
 * @group SecurityBlockerBackofficeGui
 * @group Communication
 * @group EventSubscriber
 * @group SecurityBlockerBackOfficeUserEventSubscriberTest
 * Add your own group annotations below this line
 */
class SecurityBlockerBackOfficeUserEventSubscriberTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiConfig::SECURITY_BLOCKER_BACK_OFFICE_USER_ENTITY_TYPE
     *
     * @var string
     */
    protected const BACK_OFFICE_USER_TYPE = 'back-office-user';

    /**
     * @uses \Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiConfig::BACK_OFFICE_LOGIN_CHECK_URL
     *
     * @var string
     */
    protected const LOGIN_CHECK_URL = '/login_check';

    /**
     * @var \SprykerTest\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiCommunicationTester
     */
    protected SecurityBlockerBackofficeGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\SecurityBlockerBackofficeGui\Communication\EventSubscriber\SecurityBlockerBackofficeUserEventSubscriber
     */
    protected SecurityBlockerBackofficeUserEventSubscriber $subscriber;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Client\SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface
     */
    protected SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface $securityBlockerClientMock;

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
        $this->subscriber = $this->createSecurityBlockerBackOfficeUserEventSubscriber();
    }

    /**
     * @return void
     */
    public function testSecurityBlockerBackOfficeUserEventSubscriberShouldCallSecurityBlockerClientOnKernelRequestWhileSendingValidLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::BACK_OFFICE_USER_TYPE);
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
    public function testSecurityBlockerBackOfficeUserEventSubscriberShouldThrowHttpExceptionOnKernelRequestWhileSendingBlockedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::BACK_OFFICE_USER_TYPE);
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
    public function testSecurityBlockerBackOfficeUserEventSubscriberShouldNotCallSecurityBlockerClientWhileSendingWrongRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::BACK_OFFICE_USER_TYPE);
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
    public function testSecurityBlockerBackOfficeUserEventSubscriberShouldReturnSecurityBlockerClientExceptionWhileFailedLoginAttempt(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::BACK_OFFICE_USER_TYPE);

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
    public function testSecurityBlockerBackOfficeUserEventSubscriberShouldNotCallSecurityBlockerClientWhileSendingValidGetRequest(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = $this->tester->createSecurityCheckAuthContextTransfer(static::BACK_OFFICE_USER_TYPE);

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
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = $this->tester->createRequest($requestMethod, $securityCheckAuthContextTransfer);

        return new RequestEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStackMock(): RequestStack
    {
        return $this->createMock(RequestStack::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Client\SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface
     */
    protected function createSecurityBlockerClientMock(): SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface
    {
        return $this->createMock(SecurityBlockerBackofficeGuiToSecurityBlockerClientInterface::class);
    }

    /**
     * @return \Spryker\Zed\SecurityBlockerBackofficeGui\Communication\EventSubscriber\SecurityBlockerBackofficeUserEventSubscriber
     */
    protected function createSecurityBlockerBackOfficeUserEventSubscriber(): SecurityBlockerBackofficeUserEventSubscriber
    {
        return new SecurityBlockerBackofficeUserEventSubscriber(
            $this->requestStackMock,
            $this->securityBlockerClientMock,
            $this->getMockBuilder(MessageBuilder::class)->disableOriginalConstructor()->getMock(),
            $this->createConfiguredMock(
                SecurityBlockerBackofficeGuiConfig::class,
                [
                    'getBackofficeUserLoginCheckUrl' => static::LOGIN_CHECK_URL,
                    'getSecurityBlockerBackofficeUserEntityType' => static::BACK_OFFICE_USER_TYPE,
                ],
            ),
        );
    }
}
