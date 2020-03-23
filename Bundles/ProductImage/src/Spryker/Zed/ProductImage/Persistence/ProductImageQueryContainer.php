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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param array $excludeIdProductImageSets
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract, array $excludeIdProductImageSets = [])
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByIdProductImageSet($excludeIdProductImageSets, Criteria::NOT_IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param array $excludeIdProductImageSets
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductId($idProduct, array $excludeIdProductImageSets = [])
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
            ->filterByFkProduct($idProduct)
            ->filterByIdProductImageSet($excludeIdProductImageSets, Criteria::NOT_IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductImageSet
     * @param array $excludeIdProductImage
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImageByProductImageSetId($idProductImageSet, array $excludeIdProductImage = [])
    {
        return $this
            ->queryProductImageSetToProductImage()
            ->useSpyProductImageQuery()
                ->filterByIdProductImage($excludeIdProductImage, Criteria::NOT_IN)
                ->endUse()
            ->filterByFkProductImageSet($idProductImageSet);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryDefaultAbstractProductImageSets($idProductAbstract)
    {
        return $this->queryProductImageSet()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale(null);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryLocalizedAbstractProductImageSets($idProductAbstract, $idLocale)
    {
        return $this->queryProductImageSet()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale($idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryDefaultConcreteProductImageSets($idProductConcrete)
    {
        return $this->queryProductImageSet()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale(null);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryLocalizedConcreteProductImageSets($idProductConcrete, $idLocale)
    {
        return $this->queryProductImageSet()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale($idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetById($idProductImageSet)
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
            ->filterByIdProductImageSet($idProductImageSet)
            ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                ->orderBySortOrder(Criteria::DESC)
            ->endUse();
    }
}
