<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Spryker\Yves\Kernel\Plugin\Pimple;

/**
 * @method \Spryker\Yves\Kernel\Plugin\Application getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class KernelApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_RESOLVER = 'resolver';
    protected const SERVICE_MONOLOG_LOG_LEVEL = 'monolog.level';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $this->setPimpleApplication($container);

        $container = $this->addControllerResolver($container);
        $container = $this->addLogLevel($container);
        $container = $this->addDebugMode($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function setPimpleApplication(ContainerInterface $container): void
    {
        $pimplePlugin = new Pimple();
        $pimplePlugin->setApplication($container);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface $container
     */
    protected function addDebugMode(ContainerInterface $container): ContainerInterface
    {
        $container->set('debug', function () {
            return Config::get(KernelConstants::ENABLE_APPLICATION_DEBUG, false);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface $container
     */
    protected function addControllerResolver(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_RESOLVER, function (ContainerInterface $container) {
            return new YvesFragmentControllerResolver($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface $container
     */
    protected function addLogLevel(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_MONOLOG_LOG_LEVEL, function () {
            return Config::get(KernelConstants::LOG_LEVEL);
        });

        return $container;
    }
}
