<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductQueryContainer extends AbstractQueryContainer implements ProductQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductWithAttributesAndProductAbstract($concreteSku, $idLocale)
    {
        $query = $this->getFactory()->createProductQuery();

        $query->filterBySku($concreteSku)
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->useSpyProductAbstractQuery()
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this->getFactory()->createProductAbstractQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct()
    {
        return $this->getFactory()->createProductQuery();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedAttributes($idProductAbstract)
    {
        $query = $this->getFactory()->createProductAbstractLocalizedAttributesQuery();
        $query->filterByFkProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryAllProductAbstractLocalizedAttributes()
    {
        return $this->getFactory()->createProductAbstractLocalizedAttributesQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryAllProductLocalizedAttributes()
    {
        return $this->getFactory()->createProductLocalizedAttributesQuery();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductLocalizedAttributes($idProduct)
    {
        $query = $this->getFactory()->createProductLocalizedAttributesQuery();
        $query->filterByFkProduct($idProduct);

        return $query;
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku)
    {
        return $this->getFactory()->createProductQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractAttributeCollection($idProductAbstract, $fkCurrentLocale)
    {
        $query = $this->getFactory()->createProductAbstractLocalizedAttributesQuery();
        $query
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductConcreteAttributeCollection($idProductConcrete, $fkCurrentLocale)
    {
        $query = $this->getFactory()->createProductLocalizedAttributesQuery();
        $query
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()->createProductAttributeKeyQuery();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        return $this->getFactory()
            ->getUrlQueryContainer()
            ->queryUrls()
            ->filterByFkResourceProductAbstract($idProductAbstract)
            ->filterByFkLocale($idLocale);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithName($idLocale)
    {
        return $this->queryProductAbstract()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
                ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, 'name');
    }
}
