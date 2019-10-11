<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\RedirectAfterLoginEventDispatcherPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Communication
 * @group Plugin
 * @group EventDispatcher
 * @group RedirectAfterLoginEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class RedirectAfterLoginEventDispatcherPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\RedirectAfterLoginEventDispatcherPlugin::REFERER
     */
    protected const REFERER = 'referer';
    protected const REQUEST_URI = 'REQUEST_URI';
    protected const REDIRECT_URL_VALID = '/valid-redirect-url?query=string';
    protected const REDIRECT_URL_INVALID = 'http://foo/redirect-url?query=string';

    /**
     * @return void
     */
    public function setUp(): void
    {
        Request::setTrustedHosts([]);
        Request::setTrustedProxies([], Request::HEADER_X_FORWARDED_ALL);
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldSetRefererWhenRedirectingToLogin(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();

        $request->server->set(static::REQUEST_URI, static::REDIRECT_URL_VALID);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_LOGIN);

        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->never())
            ->method('isAuthenticated');

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertSame('/auth/login?referer=%2Fvalid-redirect-url%3Fquery%3Dstring', $event->getResponse()->headers->get('location'));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotSetInvalidRefererWhenRedirectingToLogin(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();
        $request->setMethod('POST');
        $request->server->set(static::REQUEST_URI, static::REDIRECT_URL_VALID);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_LOGIN);

        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->never())
            ->method('isAuthenticated');

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertSame('/auth/login', $event->getResponse()->headers->get('location'));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotChangeResponseIfRedirectUriNotSetInReferer(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotUseInvalidReferer(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set(static::REFERER, static::REDIRECT_URL_INVALID);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertSame('/', $event->getResponse()->headers->get('location'));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseMustNotSetRedirectUriIfRedirectUriSetAndUserIsNotAuthenticated(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set(static::REFERER, static::REDIRECT_URL_VALID);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseMustSetRedirectResponseIfRedirectUriSetInRefererAndUserIsAuthenticated(): void
    {
        $kernel = $this->getHttpKernel();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set(static::REFERER, static::REDIRECT_URL_VALID);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginEventDispatcherPlugin = $this->getRedirectAfterLoginEventDispatcherPlugin(['isAuthenticated']);
        $redirectAfterLoginEventDispatcherPlugin->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $event = $this->dispatchEvent($event, $redirectAfterLoginEventDispatcherPlugin);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());

        $this->assertSame(static::REDIRECT_URL_VALID, $event->getResponse()->headers->get('location'));
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     * @param \Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\RedirectAfterLoginEventDispatcherPlugin $redirectAfterLoginEventDispatcherPlugin
     *
     * @return \Symfony\Component\HttpKernel\Event\FilterResponseEvent
     */
    protected function dispatchEvent(FilterResponseEvent $event, RedirectAfterLoginEventDispatcherPlugin $redirectAfterLoginEventDispatcherPlugin): FilterResponseEvent
    {
        $eventDispatcher = new EventDispatcher();
        $redirectAfterLoginEventDispatcherPlugin->extend($eventDispatcher, new Container());

        /** @var \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event */
        $event = $eventDispatcher->dispatch($event, KernelEvents::RESPONSE);

        return $event;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\RedirectAfterLoginEventDispatcherPlugin
     */
    protected function getRedirectAfterLoginEventDispatcherPlugin(array $methods = [])
    {
        if (!$methods) {
            return new RedirectAfterLoginEventDispatcherPlugin();
        }

        return $this->getMockBuilder(RedirectAfterLoginEventDispatcherPlugin::class)
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpKernel()
    {
        return $this->getMockBuilder(HttpKernelInterface::class)->getMock();
    }
}
