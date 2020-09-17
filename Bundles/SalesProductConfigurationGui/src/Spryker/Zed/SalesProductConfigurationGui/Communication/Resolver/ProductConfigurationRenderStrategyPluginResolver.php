<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface;

class ProductConfigurationRenderStrategyPluginResolver implements ProductConfigurationRenderStrategyPluginResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface[]
     */
    protected $productConfigurationRenderPlugins;

    /**
     * @param \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface[] $productConfigurationRenderPlugins
     */
    public function __construct(array $productConfigurationRenderPlugins)
    {
        $this->productConfigurationRenderPlugins = $productConfigurationRenderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface|null
     */
    public function resolveProductConfigurationRenderPlugin(ItemTransfer $itemTransfer): ?ProductConfigurationRenderPluginInterface
    {
        foreach ($this->productConfigurationRenderPlugins as $productConfigurationRenderPlugin) {
            if ($productConfigurationRenderPlugin->isApplicable($itemTransfer)) {
                return $productConfigurationRenderPlugin;
            }
        }

        return null;
    }
}
