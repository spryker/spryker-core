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
     *
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
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
     *
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
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
     *
     * @return array
     */
    public function getAllProductCategoriesWithFilters()
    {
        return $this->getFactory()
            ->createProductCategoryFilterReader()
            ->getAllProductCategoriesIdsWithFilters();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
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
     *
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $this->getFactory()
            ->createProductCategoryFilterDeleter()
            ->deleteProductCategoryFilterByCategoryId($categoryId);
    }
}
