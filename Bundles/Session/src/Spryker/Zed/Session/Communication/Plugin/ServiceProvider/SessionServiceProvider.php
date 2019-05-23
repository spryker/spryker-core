<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 */
class SessionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        $this->setSessionStorageOptions($application);
        $this->setSessionStorageHandler($application);
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    protected function setSessionStorageOptions(Application $application)
    {
        $application['session.storage.options'] = $this->getFactory()->createSessionStorage()->getOptions();
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    protected function setSessionStorageHandler(Application $application)
    {
        $application['session.storage.handler'] = function () {
            return $this->getFactory()->createSessionStorage()->getAndRegisterHandler();
        };
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function boot(Application $application)
    {
        if ($this->isCliOrPhpDbg()) {
            return;
        }

        $session = $this->getSession($application);
        $this->getSessionClient()->setContainer($session);
        $application['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'extendCookieLifetime'], -128);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    public function extendCookieLifetime(FilterResponseEvent $event): void
    {
        if ($event->isMasterRequest() === false) {
            return;
        }

        $request = $event->getRequest();

        $session = $request->hasPreviousSession() ? $request->getSession() : null;

        if ($session === null || $session->isStarted() === false) {
            return;
        }

        $session->save();

        $params = session_get_cookie_params();

        $event->getResponse()->headers->setCookie(new Cookie(
            $session->getName(),
            $session->getId(),
            $params['lifetime'] === 0 ? 0 : time() + $params['lifetime'],
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        ));
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getFactory()->getSessionClient();
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession(Application $application)
    {
        return $application['session'];
    }

    /**
     * @return bool
     */
    protected function isCliOrPhpDbg()
    {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg');
    }
}
