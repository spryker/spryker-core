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
    protected $productOptionValuesPreRemovePlugins;

    /**
     * @param \Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface[] $productOptionValuesPreRemovePlugins
     */
    public function __construct(array $productOptionValuesPreRemovePlugins)
    {
        $this->productOptionValuesPreRemovePlugins = $productOptionValuesPreRemovePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function executePreRemoveProductOptionValuePlugins(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        foreach ($this->productOptionValuesPreRemovePlugins as $plugin) {
            $plugin->preRemove($productOptionGroupTransfer);
        }
    }
}
