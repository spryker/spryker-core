<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SessionApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    protected const SERVICE_SESSION = 'session';
    protected const SERVICE_SESSION_TEST = 'session.test';

    /**
     * {@inheritDoc}
     * - Adds `session.test` flag as service. It's false by default and can be enabled for test purposes.
     * - Adds `session` service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addSessionTestService($container);
        $container = $this->addSessionService($container);

        return $container;
    }

    /**
     * {@inheritDoc}
     * - Adds `session` service to `SessionClient`.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        if ($this->isCliOrPhpDbg()) {
            return $container;
        }

        $searchClient = $this->getFactory()->getSessionClient();
        $searchClient->setContainer($this->getSessionService($container));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionTestService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SESSION_TEST, false);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SESSION, function (ContainerInterface $container) {
            return new Session($this->createSessionStorage($container));
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    protected function createSessionStorage(ContainerInterface $container): SessionStorageInterface
    {
        if ($this->isSessionTestServiceEnabled($container)) {
            return $this->getFactory()->createMockSessionStorage();
        }

        return $this->getFactory()->createNativeSessionStorage();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return bool
     */
    protected function isSessionTestServiceEnabled(ContainerInterface $container): bool
    {
        return $container->has(static::SERVICE_SESSION_TEST) && $container->get(static::SERVICE_SESSION_TEST);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function getSessionService(ContainerInterface $container): SessionInterface
    {
        return $container->get(static::SERVICE_SESSION);
    }

    /**
     * @return bool
     */
    protected function isCliOrPhpDbg()
    {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg');
    }
}
