<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Application;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueApplicationApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';
    protected const DEBUG = 'debug';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addDebugMode($container);
        $container = $this->addResourceBuilder($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addDebugMode(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::DEBUG, function () {
            return $this->getConfig()->getIsRestDebugEnabled();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addResourceBuilder(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_RESOURCE_BUILDER, function () {
            return $this->getFactory()->createRestResourceBuilder();
        });

        return $container;
    }
}
