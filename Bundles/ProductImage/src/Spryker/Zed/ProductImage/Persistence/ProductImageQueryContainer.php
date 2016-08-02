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
            ->orderBySort(Criteria::DESC);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageProduct()
    {
        return $this->getFactory()
            ->createProductImageSetQuery()
            ->useSpyProductImageSetToProductImageQuery()
                ->useSpyProductImageQuery()
                ->endUse()
            ->orderBySort(Criteria::DESC)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageQuery()
                ->useSpyProductImageSetToProductImageQuery()
                    ->useSpyProductImageSetQuery()
                        ->filterByFkProductAbstract($idProductAbstract)
                    ->endUse()
                ->orderBySort(Criteria::DESC)
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
        return $this->queryImageProduct()
            ->filterByFkProduct($idProduct);
    }

}
