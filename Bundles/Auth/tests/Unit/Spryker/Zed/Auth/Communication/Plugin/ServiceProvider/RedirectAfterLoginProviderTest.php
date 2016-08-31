<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Auth\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Auth
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group RedirectAfterLoginProviderTest
 */
class RedirectAfterLoginProviderTest extends \PHPUnit_Framework_TestCase
{

    const REQUEST_URI = 'REQUEST_URI';
    const VALID_REDIRECT_URL = '/valid-redirect-url?query=string';

    /**
     * @return void
     */
    public function testOnKernelResponseShouldSetRefererWhenRedirectingToLogin()
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, static::VALID_REDIRECT_URL);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_LOGIN);

        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->never())
            ->method('isAuthenticated');
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertSame('/auth/login?referer=%2Fvalid-redirect-url%3Fquery%3Dstring', $event->getResponse()->headers->get('location'));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotChangeResponseIfRedirectUriNotSetInReferer()
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->never())
            ->method('isAuthenticated');
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseMustNotSetRedirectUriInSessionIfRedirectUriSetInSessionAndUserIsNotAuthenticated()
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set(RedirectAfterLoginProvider::REFERER, static::VALID_REDIRECT_URL);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseMustSetRedirectResponseIfRedirectUriSetInRefererAndUserIsAuthenticated()
    {
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();
        $request = new Request();
        $request->server->set(static::REQUEST_URI, AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set(RedirectAfterLoginProvider::REFERER, static::VALID_REDIRECT_URL);
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertInstanceOf(RedirectResponse::class, $event->getResponse());

        $this->assertSame(static::VALID_REDIRECT_URL, $event->getResponse()->headers->get('location'));
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider
     */
    private function getRedirectAfterLoginProvider(array $methods = [])
    {
        if (empty($methods)) {
            return new RedirectAfterLoginProvider();
        }

        return $this->getMockBuilder(RedirectAfterLoginProvider::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

}
