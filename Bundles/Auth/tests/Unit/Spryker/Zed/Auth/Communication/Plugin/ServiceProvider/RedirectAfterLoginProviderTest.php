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

    const VALID_REDIRECT_URL = '/valid-redirect-url';

    /**
     * @return void
     */
    public function testOnKernelResponseShouldSetRefererWhenRedirectingToLogin()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->server->set('REQUEST_URI', '/foo');
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_LOGIN);

        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->never())
            ->method('isAuthenticated');
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertSame('/auth/login?referer=%2Ffoo', $event->getResponse()->headers->get('location'));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotChangeResponseIfRedirectUriNotSetInReferer()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->server->set('REQUEST_URI', AuthConfig::DEFAULT_URL_LOGIN);
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
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->server->set('REQUEST_URI', AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set('referer', '/foo');
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
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->server->set('REQUEST_URI', AuthConfig::DEFAULT_URL_LOGIN);
        $request->query->set('referer', '/foo');
        $response = new RedirectResponse(AuthConfig::DEFAULT_URL_REDIRECT);
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $event->getResponse());

        $this->assertSame('/foo', $event->getResponse()->headers->get('location'));
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

        return $this->getMock(RedirectAfterLoginProvider::class, $methods, [], '', false);
    }

}
