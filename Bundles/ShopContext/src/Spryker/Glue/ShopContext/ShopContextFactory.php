<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShopContext;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShopContext\Communication\Provider\ShopContextProvider;
use Spryker\Glue\ShopContext\Communication\Provider\ShopContextProviderInterface;

class ShopContextFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShopContext\Communication\Provider\ShopContextProviderInterface
     */
    public function createShopContextProvider(): ShopContextProviderInterface
    {
        return new ShopContextProvider(
            $this->getShopContextExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[]
     */
    public function getShopContextExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShopContextDependencyProvider::PLUGINS_SHOP_CONTEXT_EXPANDER);
    }
}
