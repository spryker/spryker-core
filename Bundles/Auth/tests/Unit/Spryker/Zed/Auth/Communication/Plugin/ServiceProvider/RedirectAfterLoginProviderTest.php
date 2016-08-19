<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Auth\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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
    public function testOnKernelRequestMustNotStoreRequestUriIfRequestIsNotGetRequest()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->setSession(new Session());
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider();
        $redirectAfterLoginProvider->onKernelRequest($event);

        $this->assertFalse($request->getSession()->has(RedirectAfterLoginProvider::REQUEST_URI));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestMustNotStoreRequestUriIfUserIsAuthenticated()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setSession(new Session());
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $redirectAfterLoginProvider->onKernelRequest($event);

        $this->assertFalse($request->getSession()->has(RedirectAfterLoginProvider::REQUEST_URI));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestMustNotStoreRequestUriIfRequestUriIsLoginUri()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', ['getRequestUri']);
        $request->expects($this->once())
            ->method('getRequestUri')
            ->willReturn(RedirectAfterLoginProvider::LOGIN_URI);
        $request->setMethod(Request::METHOD_GET);
        $request->setSession(new Session());
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $redirectAfterLoginProvider->onKernelRequest($event);

        $this->assertFalse($request->getSession()->has(RedirectAfterLoginProvider::REQUEST_URI));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestMustNotStoreRequestUriIfRequestUriIsProfilerUri()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', ['getRequestUri']);
        $request->expects($this->once())
            ->method('getRequestUri')
            ->willReturn('/_profiler');
        $request->setMethod(Request::METHOD_GET);
        $request->setSession(new Session());
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $redirectAfterLoginProvider->onKernelRequest($event);

        $this->assertFalse($request->getSession()->has(RedirectAfterLoginProvider::REQUEST_URI));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestMustStoreRequestUriIfCanRedirectAfterLogin()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', ['getRequestUri']);
        $request->expects($this->any())
            ->method('getRequestUri')
            ->willReturn(self::VALID_REDIRECT_URL);
        $request->setMethod(Request::METHOD_GET);
        $request->setSession(new Session());
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $redirectAfterLoginProvider->onKernelRequest($event);

        $this->assertSame(self::VALID_REDIRECT_URL, $request->getSession()->get(RedirectAfterLoginProvider::REQUEST_URI));
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotChangeResponseIfRedirectUriNotSetInSession()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $request->setSession(new Session());
        $response = new Response();
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider();
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseShouldNotChangeResponseIfUserNotAuthenticated()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $session = new Session();
        $session->set(RedirectAfterLoginProvider::REQUEST_URI, self::VALID_REDIRECT_URL);
        $request->setSession($session);
        $response = new Response();
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
    public function testOnKernelResponseMustSetRedirectResponseIfRedirectUriSetInSessionAndUserIsAuthenticated()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $session = new Session();
        $session->set(RedirectAfterLoginProvider::REQUEST_URI, self::VALID_REDIRECT_URL);
        $request->setSession($session);
        $response = new Response();
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $event->getResponse());
    }

    /**
     * @return void
     */
    public function testOnKernelResponseMustUnsetRedirectUriInSessionIfRedirectUriSetInSessionAndUserIsAuthenticated()
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = new Request();
        $session = new Session();
        $session->set(RedirectAfterLoginProvider::REQUEST_URI, self::VALID_REDIRECT_URL);
        $request->setSession($session);
        $response = new Response();
        $event = new FilterResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $redirectAfterLoginProvider = $this->getRedirectAfterLoginProvider(['isAuthenticated']);
        $redirectAfterLoginProvider->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);
        $redirectAfterLoginProvider->onKernelResponse($event);

        $this->assertFalse($request->getSession()->has(RedirectAfterLoginProvider::REQUEST_URI));
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
