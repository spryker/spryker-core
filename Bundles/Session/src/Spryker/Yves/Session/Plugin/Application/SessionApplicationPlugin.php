<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * @method \Spryker\Yves\Session\SessionConfig getConfig()
 * @method \Spryker\Yves\Session\SessionFactory getFactory()
 * @method \Spryker\Client\Session\SessionClientInterface getClient()
 */
class SessionApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    protected const SERVICE_SESSION = 'session';
    protected const FLAG_SESSION_TEST = 'session.test';

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
        $container = $this->addSessionTestFlag($container);
        $container = $this->addSessionService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionTestFlag(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::FLAG_SESSION_TEST, false);

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
        if ($this->isSessionTestEnabled($container)) {
            return $this->getFactory()->createMemorySessionStorage();
        }

        return $this->getFactory()->createNativeSessionStorage();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return bool
     */
    protected function isSessionTestEnabled(ContainerInterface $container): bool
    {
        return $container->has(static::FLAG_SESSION_TEST) && $container->get(static::FLAG_SESSION_TEST);
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
     * {@inheritDoc}
     * - Sets `session` service as a container for the session client.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $sessionClient = $this->getClient();
        $sessionClient->setContainer($this->getSessionService($container));

        return $container;
    }
}
