<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Session\Plugin\Application;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\Session\SessionFactory getFactory()
 */
class SessionApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_SESSION = 'session';
    protected const FLAG_SESSION_TEST = 'session.test';

    /**
     * {@inheritDoc}
     * - Adds `session.test` flag as service. It's false by default and can be enabled for test purposes.
     * - Adds `session` service that is actually a mock.
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
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SESSION, function () {
            return $this->getFactory()->createSession();
        });

        return $container;
    }
}
