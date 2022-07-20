<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Saver;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface;

class ProductCategoryFilterSaver implements ProductCategoryFilterSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface
     */
    protected ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface $productCategoryFilterFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface $productCategoryFilterFacade
     */
    public function __construct(ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface $productCategoryFilterFacade)
    {
        $this->productCategoryFilterFacade = $productCategoryFilterFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function save(ProductCategoryFilterTransfer $productCategoryFilterTransfer): ProductCategoryFilterTransfer
    {
        if (!$productCategoryFilterTransfer->getIdProductCategoryFilter()) {
            return $this->productCategoryFilterFacade->createProductCategoryFilter($productCategoryFilterTransfer);
        }

        return $this->productCategoryFilterFacade->updateProductCategoryFilter($productCategoryFilterTransfer);
    }
}
