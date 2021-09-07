<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class MerchantPortalApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_PORTAL_APPLICATION = 'PLUGINS_MERCHANT_PORTAL_APPLICATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantPortalApplicationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPortalApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_PORTAL_APPLICATION, function (Container $container): array {
            return $this->getMerchantPortalApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getMerchantPortalApplicationPlugins(): array
    {
        return [];
    }
}
