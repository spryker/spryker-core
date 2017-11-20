<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SessionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $client;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $client
     *
     * @return void
     */
    public function setClient(SessionClientInterface $client)
    {
        $this->client = $client;
    }

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
        $this->client->setContainer($session);
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
