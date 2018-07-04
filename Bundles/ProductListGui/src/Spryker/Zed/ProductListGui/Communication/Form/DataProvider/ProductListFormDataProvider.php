<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;

class ProductListFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * ProductListFormDataProvider constructor.
     *
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface $productListFacade
     */
    public function __construct(ProductListGuiToProductListFacadeInterface $productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getData(?int $idProductList = null): ProductListTransfer
    {
        $productListTransfer = new ProductListTransfer();

        if (!$idProductList) {
            return $productListTransfer;
        }

        $productListTransfer->setIdProductList($idProductList);

        return $this->productListFacade->getProductListById($productListTransfer);
    }
}
