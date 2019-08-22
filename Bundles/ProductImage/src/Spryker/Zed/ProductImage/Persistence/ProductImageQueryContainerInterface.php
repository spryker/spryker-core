<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductImageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryImagesByIdProductImageSet($idProductImageSet);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryProductImage();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImage();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryProductImageSet();

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryImageCollectionByProductAbstractId($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function queryImageCollectionByProductId($idProduct);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param array $excludeIdProductImageSets
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract, array $excludeIdProductImageSets = []);

    /**
     * @api
     *
     * @param int $idProduct
     * @param array $excludeIdProductImageSets
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductId($idProduct, array $excludeIdProductImageSets = []);

    /**
     * @api
     *
     * @param int $idProductImageSet
     * @param array $excludeIdProductImage
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImageByProductImageSetId($idProductImageSet, array $excludeIdProductImage = []);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryDefaultAbstractProductImageSets($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryLocalizedAbstractProductImageSets($idProductAbstract, $idLocale);

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryDefaultConcreteProductImageSets($idProductConcrete);

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryLocalizedConcreteProductImageSets($idProductConcrete, $idLocale);

    /**
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetById($idProductImageSet);
}
