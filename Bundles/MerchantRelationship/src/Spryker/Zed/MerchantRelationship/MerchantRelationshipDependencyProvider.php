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
    public const MERCHANT_RELATIONSHIP_PRE_DELETE_PLUGINS = 'MERCHANT_RELATIONSHIP_PRE_DELETE_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantRelationshipPreDeletePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPreDeletePlugins(Container $container): Container
    {
        $container[static::MERCHANT_RELATIONSHIP_PRE_DELETE_PLUGINS] = function () {
            return $this->getMerchantRelationshipPreDeletePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface[]
     */
    protected function getMerchantRelationshipPreDeletePlugins(): array
    {
        return [];
    }
}
