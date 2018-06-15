<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListBusinessFactory getFactory()
 */
class ProductListFacade extends AbstractFacade implements ProductListFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->createProductListWriter()
            ->saveProductList($productListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void
    {
        $this->getFactory()
            ->createProductListWriter()
            ->deleteProductList($productListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function findProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->createProductListReader()
            ->findProductList($productListTransfer);
    }
}
