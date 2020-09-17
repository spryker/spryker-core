<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface;

interface ProductConfigurationRenderStrategyPluginResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface|null
     */
    public function resolveProductConfigurationRenderPlugin(ItemTransfer $itemTransfer): ?ProductConfigurationRenderPluginInterface;
}
