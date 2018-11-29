<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface;

class PreRemoveProductOptionValuePluginExecutor implements PreRemoveProductOptionValuePluginExecutorInterface
{
    /**
     * @var ProductOptionValuesPreRemovePluginInterface[]
     */
    protected $plugins;

    /**
     * @param ProductOptionValuesPreRemovePluginInterface[] $plugins
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
    public function executePreRemoveOptionValuesPlugins(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->preRemove($productOptionGroupTransfer);
        }
    }
}
