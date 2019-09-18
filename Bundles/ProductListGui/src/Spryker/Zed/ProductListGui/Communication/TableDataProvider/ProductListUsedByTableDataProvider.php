<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\TableDataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;

class ProductListUsedByTableDataProvider implements ProductListUsedByTableDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableDataExpanderPluginInterface[]
     */
    protected $productListUsedByTableDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableDataExpanderPluginInterface[] $productListUsedByTableDataExpanderPlugins
     */
    public function __construct(array $productListUsedByTableDataExpanderPlugins)
    {
        $this->productListUsedByTableDataExpanderPlugins = $productListUsedByTableDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function getTableData(ProductListTransfer $productListTransfer): ProductListUsedByTableDataTransfer
    {
        $productListUsedByTableDataTransfer = (new ProductListUsedByTableDataTransfer())->setProductList($productListTransfer);

        return $this->expandProductListUsedByTableDataTransfer($productListUsedByTableDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    protected function expandProductListUsedByTableDataTransfer(
        ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
    ): ProductListUsedByTableDataTransfer {
        foreach ($this->productListUsedByTableDataExpanderPlugins as $productListUsedByTableDataExpanderPlugin) {
            $productListUsedByTableDataTransfer = $productListUsedByTableDataExpanderPlugin->expand($productListUsedByTableDataTransfer);
        }

        return $productListUsedByTableDataTransfer;
    }
}
