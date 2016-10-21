<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImagePersistenceFactory getFactory()
 */
class ProductImageQueryContainer extends AbstractQueryContainer implements ProductImageQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryImagesByIdProductImageSet($idProductImageSet)
    {
        return $this->getFactory()
            ->createProductImageSetToProductImageQuery()
                ->useSpyProductImageQuery()
                ->endUse()
            ->filterByFkProductImageSet($idProductImageSet)
            ->orderBySortOrder(Criteria::DESC);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryProductImage()
    {
        return $this->getFactory()
            ->createProductImageQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImage()
    {
        return $this->getFactory()
            ->createProductImageSetToProductImageQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryProductImageSet()
    {
        return $this->getFactory()
            ->createProductImageSetQuery();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryImageCollectionByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageQuery()
                ->useSpyProductImageSetToProductImageQuery()
                    ->useSpyProductImageSetQuery()
                        ->filterByFkProductAbstract($idProductAbstract)
                    ->endUse()
                ->orderBySortOrder(Criteria::DESC)
                ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryImageCollectionByProductId($idProduct)
    {
        return $this->getFactory()
            ->createProductImageQuery()
            ->useSpyProductImageSetToProductImageQuery()
            ->useSpyProductImageSetQuery()
            ->filterByFkProduct($idProduct)
            ->endUse()
            ->orderBySortOrder(Criteria::DESC)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
                ->filterByFkProductAbstract($idProductAbstract)
                ->useSpyProductImageSetToProductImageQuery()
                    ->useSpyProductImageQuery()
                    ->endUse()
                ->orderBySortOrder(Criteria::DESC)
                ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductId($idProduct)
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
                ->filterByFkProduct($idProduct)
                ->useSpyProductImageSetToProductImageQuery()
                    ->useSpyProductImageQuery()
                    ->endUse()
                ->orderBySortOrder(Criteria::DESC)
                ->endUse();
    }

}
