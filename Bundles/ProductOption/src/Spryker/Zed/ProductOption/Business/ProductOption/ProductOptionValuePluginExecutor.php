<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

class ProductOptionValuePluginExecutor implements ProductOptionValuePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface[]
     */
    protected $plugins;

    /**
     * @param \Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface[] $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function executeProductOptionValuePlugins(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->preRemove($productOptionGroupTransfer);
        }
    }
}
