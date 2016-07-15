<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityQueryContainer extends AbstractQueryContainer implements AvailabilityQueryContainerInterface
{

    const SUM_QUANTITY = 'sumQuantity';
    const ABSTRACT_SKU = 'abstractSku';

    /**
     * @param string $sku
     *
     * @return SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku)
    {
        $query = $this->getFactory()->createSpyAvailabilityQuery();
        $query->filterBySku($sku);

        return $query;
    }

    /**
     * @param string $abstractSku
     *
     * @return SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku)
    {
        return $this->getFactory()->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku);
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract)
    {
        return $this->getFactory()->createSpyAvailabilityAbstractQuery()
            ->filterByIdAvailabilityAbstract($idAvailabilityAbstract);
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
    {
        return $this->getFactory()->createSpyAvailabilityQuery()
            ->filterByFkAvailabilityAbstract($idAvailabilityAbstract)
            ->withColumn('SUM(' . SpyAvailabilityTableMap::COL_QUANTITY . ')', self::SUM_QUANTITY)
            ->select([self::SUM_QUANTITY]);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function querySpyProductBySku($sku)
    {
        return $this->getFactory()->getProductQueryContainer()->queryProductConcreteBySku($sku)
            ->innerJoinSpyProductAbstract()
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, self::ABSTRACT_SKU);
    }
}
