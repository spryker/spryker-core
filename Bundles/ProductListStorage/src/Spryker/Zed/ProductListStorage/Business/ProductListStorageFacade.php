<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductListStorage\Business\ProductListStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface getRepository()
 */
class ProductListStorageFacade extends AbstractFacade implements ProductListStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstract(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductListProductAbstractStorageWriter()
            ->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publishProductConcrete(array $productConcreteIds): void
    {
        $this->getFactory()
            ->createProductListProductConcreteStorageWriter()
            ->publish($productConcreteIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->createProductAbstractReader()
            ->findProductAbstractIdsByProductConcreteIds($productConcreteIds);
    }

    /**
     * {@inheritDoc}
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
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductConcreteReader()
            ->findProductConcreteIdsByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productListIds
     *
     * @return void
     */
    public function publishProductList(array $productListIds): void
    {
        $this->getFactory()
            ->createProductListStorageWriter()
            ->publish($productListIds);
    }
}
