<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE = 'PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE';
    public const PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE = 'PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE';
    public const PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE = 'PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantRelationshipPreDeletePlugins($container);
        $container = $this->addMerchantRelationshipPostCreatePlugins($container);
        $container = $this->addMerchantRelationshipPostUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPreDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE, function () {
            return $this->getMerchantRelationshipPreDeletePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE, function () {
            return $this->getMerchantRelationshipPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE, function () {
            return $this->getMerchantRelationshipPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface[]
     */
    protected function getMerchantRelationshipPreDeletePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface[]
     */
    protected function getMerchantRelationshipPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface[]
     */
    protected function getMerchantRelationshipPostUpdatePlugins(): array
    {
        return [];
    }
}
