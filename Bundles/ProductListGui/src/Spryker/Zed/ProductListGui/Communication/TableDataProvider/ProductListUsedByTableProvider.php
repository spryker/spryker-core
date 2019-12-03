<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\TableDataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableTransfer;

class ProductListUsedByTableProvider implements ProductListUsedByTableProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableExpanderPluginInterface[]
     */
    protected $productListUsedByTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableExpanderPluginInterface[] $productListUsedByTableExpanderPlugins
     */
    public function __construct(array $productListUsedByTableExpanderPlugins)
    {
        $this->productListUsedByTableExpanderPlugins = $productListUsedByTableExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function getTableData(ProductListTransfer $productListTransfer): ProductListUsedByTableTransfer
    {
        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())->setProductList($productListTransfer);

        return $this->expandProductListUsedByTableTransfer($productListUsedByTableTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    protected function expandProductListUsedByTableTransfer(
        ProductListUsedByTableTransfer $productListUsedByTableTransfer
    ): ProductListUsedByTableTransfer {
        foreach ($this->productListUsedByTableExpanderPlugins as $productListUsedByTableExpanderPlugin) {
            $productListUsedByTableTransfer = $productListUsedByTableExpanderPlugin->expand($productListUsedByTableTransfer);
        }

        return $productListUsedByTableTransfer;
    }
}
