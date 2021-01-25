<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ShopContext;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ShopContextDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_SHOP_CONTEXT_EXPANDER = 'PLUGINS_SHOP_CONTEXT_EXPANDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addShopContextExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShopContextExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHOP_CONTEXT_EXPANDER, function () {
            return $this->getShopContextExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[]
     */
    protected function getShopContextExpanderPlugins(): array
    {
        return [];
    }
}
