<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Shared\Config\Config;

/**
 * @method \Spryker\Service\UtilDateTime\UtilDateTimeConfig getConfig()
 */
class UtilDateTimeDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CONFIG = 'config';

    /**
     * @var string
     */
    public const SERVICE_TIMEZONE = 'SERVICE_TIMEZONE';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addConfig($container);
        $container = $this->addTimezoneFromApplicationContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addConfig(Container $container)
    {
        $container->set(static::CONFIG, function () {
            return Config::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addTimezoneFromApplicationContainer(Container $container): Container
    {
        $container->set(static::SERVICE_TIMEZONE, function (Container $container) {
            if (!$container->hasApplicationService(static::SERVICE_TIMEZONE)) {
                return null;
            }

            return $container->getApplicationService(static::SERVICE_TIMEZONE);
        });

        return $container;
    }
}
