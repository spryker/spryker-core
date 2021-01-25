<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Service\Container\Container;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Zed\Acl\Communication\Plugin\EventDispatcher\AccessControlEventDispatcherPlugin;
use Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Plugin
 * @group EventDispatcher
 * @group AccessControlEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class AccessControlEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Acl\AclCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOnKernelRequestDoesNotRedirectWhenResourceIsIgnorable(): void
    {
        $this->tester->mockFacadeMethod('isIgnorable', true);
        $aclFacade = $this->tester->getFacade();
        $accessControlEventDispatcherPlugin = new AccessControlEventDispatcherPlugin();
        $accessControlEventDispatcherPlugin->setFacade($aclFacade);

        $event = $this->dispatchEvent($accessControlEventDispatcherPlugin);

        $this->assertNull($event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelRequestDoesNotRedirectWhenCurrentUserCanAccessResource(): void
    {
        $this->tester->mockFacadeMethod('hasCurrentUser', true);
        $this->tester->mockFacadeMethod('getCurrentUser', new UserTransfer());
        $this->tester->mockFacadeMethod('checkAccess', true);

        $aclFacade = $this->tester->getFacade();
        $accessControlEventDispatcherPlugin = new AccessControlEventDispatcherPlugin();
        $accessControlEventDispatcherPlugin->setFacade($aclFacade);

        $event = $this->dispatchEvent($accessControlEventDispatcherPlugin);

        $this->assertNull($event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelRequestRedirectsWhenCurrentUserIsNotExists(): void
    {
        $this->tester->mockFacadeMethod('hasCurrentUser', false);

        $aclFacade = $this->tester->getFacade();
        $accessControlEventDispatcherPlugin = new AccessControlEventDispatcherPlugin();
        $accessControlEventDispatcherPlugin->setFacade($aclFacade);

        $event = $this->dispatchEvent($accessControlEventDispatcherPlugin);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelRequestRedirectsWhenCurrentUserIsNotAllowedToAccessResource(): void
    {
        $this->tester->mockFacadeMethod('hasCurrentUser', true);
        $this->tester->mockFacadeMethod('getCurrentUser', new UserTransfer());
        $this->tester->mockFacadeMethod('checkAccess', false);

        $aclFacade = $this->tester->getFacade();
        $accessControlEventDispatcherPlugin = new AccessControlEventDispatcherPlugin();
        $accessControlEventDispatcherPlugin->setFacade($aclFacade);

        $event = $this->dispatchEvent($accessControlEventDispatcherPlugin);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());
    }

    /**
     * @param \Spryker\Zed\Acl\Communication\Plugin\EventDispatcher\AccessControlEventDispatcherPlugin $accessControlEventDispatcherPlugin
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function dispatchEvent(AccessControlEventDispatcherPlugin $accessControlEventDispatcherPlugin): RequestEvent
    {
        $eventDispatcher = new EventDispatcher();
        $container = new Container();
        $accessControlEventDispatcherPlugin->extend($eventDispatcher, $container);

        $requestAttributesEventDispatcherPluginMock = $this->getRequestAttributesEventDispatcherPluginMock();
        $requestAttributesEventDispatcherPluginMock->extend($eventDispatcher, $container);

        /** @var \Symfony\Component\HttpKernel\Event\RequestEvent $event */
        $event = $eventDispatcher->dispatch($this->tester->getRequestEvent(), KernelEvents::REQUEST);

        return $event;
    }

    /**
     * @param bool $isCliRequest
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin
     */
    protected function getRequestAttributesEventDispatcherPluginMock(bool $isCliRequest = false): RequestAttributesEventDispatcherPlugin
    {
        $requestAttributesEventDispatcherPluginMock = $this->getMockBuilder(RequestAttributesEventDispatcherPlugin::class)
            ->onlyMethods(['isCli'])
            ->getMock();

        $requestAttributesEventDispatcherPluginMock->method('isCli')->willReturn($isCliRequest);

        return $requestAttributesEventDispatcherPluginMock;
    }
}
