<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;

/**
 * @method \Spryker\Yves\Session\SessionConfig getConfig()
 * @method \Spryker\Yves\Session\SessionFactory getFactory()
 * @method \Spryker\Client\Session\SessionClientInterface getClient()
 */
class ConsoleSessionApplicationPlugin extends SessionApplicationPlugin
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addSessionTestFlag(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::FLAG_SESSION_TEST, true);

        return $container;
    }
}
