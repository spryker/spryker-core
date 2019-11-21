<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session\Plugin\Application\Console;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Session\SessionConfig getConfig()
 * @method \Spryker\Yves\Session\SessionFactory getFactory()
 * @method \Spryker\Client\Session\SessionClientInterface getClient()
 */
class ConsoleSessionApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    protected const SERVICE_SESSION = 'session';
    protected const FLAG_SESSION_TEST = 'session.test';

    /**
     * {@inheritDoc}
     * - Adds `session.test` flag as service. It's false by default and can be enabled for test purposes.
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

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionTestService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::FLAG_SESSION_TEST, false);

        return $container;
    }

    /**
     * {@inheritDoc}
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
        $sessionClient->setContainer($this->getFactory()->createConsoleSession());

        return $container;
    }
}
