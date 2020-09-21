<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer;

class ProductConfigurationTemplateResolver implements ProductConfigurationTemplateResolverInterface
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
     * @return \Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer|null
     */
    public function resolveProductConfigurationTemplate(ItemTransfer $itemTransfer): ?SalesProductConfigurationTemplateTransfer
    {
        foreach ($this->productConfigurationRenderPlugins as $productConfigurationRenderPlugin) {
            if ($productConfigurationRenderPlugin->isApplicable($itemTransfer)) {
                return $productConfigurationRenderPlugin->getTemplate($itemTransfer);
            }
        }

        return null;
    }
}
