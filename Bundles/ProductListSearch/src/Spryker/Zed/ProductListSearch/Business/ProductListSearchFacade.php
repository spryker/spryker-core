<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

use Generated\Shared\Transfer\ProductListMapTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface getRepository()
 */
class ProductListSearchFacade extends AbstractFacade implements ProductListSearchFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $concreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array
    {
        return $this->getFactory()
            ->createProductAbstractReader()
            ->getProductAbstractIdsByConcreteIds($concreteIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        return $this->getFactory()
            ->createProductAbstractReader()
            ->getProductAbstractIdsByCategoryIds($categoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductListMapTransfer $productListMapTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListMapTransfer
     */
    public function mapProductDataToProductListMapTransfer(array $productData, ProductListMapTransfer $productListMapTransfer): ProductListMapTransfer
    {
        return $this->getFactory()
            ->createProductDataToProductListMapTransferMapper()
            ->mapProductDataProductList($productData, $productListMapTransfer);
    }
}
