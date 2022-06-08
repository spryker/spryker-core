<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface;

class ProductCategoryFilterDataProvider
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface
     */
    protected $productCategoryFilterFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface $navigationFacade
     */
    public function __construct(ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface $navigationFacade)
    {
        $this->productCategoryFilterFacade = $navigationFacade;
    }

    /**
     * @param int|null $idProductCategoryFilter
     *
     * @return array
     */
    public function getData($idProductCategoryFilter = null)
    {
        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();

        if ($idProductCategoryFilter !== null) {
            $productCategoryFilterTransfer = $this->productCategoryFilterFacade->findProductCategoryFilterByCategoryId($idProductCategoryFilter);
        }

        return $productCategoryFilterTransfer->modifiedToArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [];
    }
}
