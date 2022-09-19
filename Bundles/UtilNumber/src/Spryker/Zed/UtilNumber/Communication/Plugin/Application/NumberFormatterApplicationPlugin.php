<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilNumber\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\UtilNumber\Communication\UtilNumberCommunicationFactory getFactory()
 * @method \Spryker\Zed\UtilNumber\UtilNumberConfig getConfig()
 */
class NumberFormatterApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

    /**
     * {@inheritDoc}
     * - Provides number formatter service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addUtilNumberService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUtilNumberService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_UTIL_NUMBER, function () {
            return $this->getFactory()->getUtilNumberService();
        });

        return $container;
    }
}
