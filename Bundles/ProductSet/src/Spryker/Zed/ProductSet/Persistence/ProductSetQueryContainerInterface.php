<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Persistence;

interface ProductSetQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSet();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function queryAllProductSetData();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductAbstractSetQuery
     */
    public function queryProductAbstractSet();

    /**
     * @api
     *
     * @param int $idProductSet
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetById($idProductSet);

    /**
     * @api
     *
     * @param int[] $productSetIds
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetByIds(array $productSetIds);

    /**
     * @api
     *
     * @param int $idProductSet
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductAbstractSetQuery
     */
    public function queryProductAbstractSetsById($idProductSet);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int|null $excludedIdProductSet
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductAbstractSetQuery
     */
    public function queryProductAbstractSetsByIdProductAbstract($idProductAbstract, $excludedIdProductSet = null);

    /**
     * @api
     *
     * @param int $idProductSet
     * @param int|null $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdProductSet($idProductSet, $idLocale = null);

    /**
     * @api
     *
     * @param int $idProductSet
     * @param int|null $idLocale
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryProductImageSet($idProductSet, $idLocale = null);

    /**
     * @api
     *
     * @param int $idProductSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryDefaultProductImageSet($idProductSet);

    /**
     * @api
     *
     * @param int $idProductSet
     * @param array $excludedIdProductImageSets
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryExcludedProductImageSet($idProductSet, array $excludedIdProductImageSets);

    /**
     * @api
     *
     * @param int $idProductSet
     * @param int|null $idLocale
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function queryProductSetDataByProductSetId($idProductSet, $idLocale = null);
}
