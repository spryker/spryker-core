<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\Business\ProductCategoryFilterBusinessFactory getFactory()
 */

class ProductCategoryFilterFacade extends AbstractFacade implements ProductCategoryFilterFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function createProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->getFactory()
            ->createProductCategoryFilterCreator()
            ->createProductCategoryFilter($productCategoryFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function findProductCategoryFilterByCategoryId($categoryId)
    {
        return $this->getFactory()
            ->createProductCategoryFilterReader()
            ->findProductCategoryFilterByCategoryId($categoryId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function updateProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->getFactory()
            ->createProductCategoryFilterUpdater()
            ->updateProductCategoryFilter($productCategoryFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $this->getFactory()
            ->createProductCategoryFilterDeleter()
            ->deleteProductCategoryFilterByCategoryId($categoryId);
    }
}
