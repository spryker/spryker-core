<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Persistence;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorPersistenceFactory getFactory()
 */
class TaxProductConnectorQueryContainer extends AbstractQueryContainer implements TaxProductConnectorQueryContainerInterface
{
    public const COL_MAX_TAX_RATE = 'MaxTaxRate';
    public const COL_ID_ABSTRACT_PRODUCT = 'IdProductAbstract';
    public const COL_COUNTRY_CODE = 'COUNTRY_CODE';

    /**
     * @api
     *
     * @todo CD-427 Follow naming conventions and use method name starting with 'query*'
     *
     * @param int $idTaxRate
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getAbstractAbstractIdsForTaxRate($idTaxRate)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ])
            ->useSpyTaxSetQuery()
                ->useSpyTaxSetTaxQuery()
                    ->useSpyTaxRateQuery()
                    ->filterByIdTaxRate($idTaxRate)
                    ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @todo CD-427 Follow naming conventions and use method name starting with 'query*'
     *
     * @param int $idTaxSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractIdsForTaxSet($idTaxSet)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->addJoin(
                SpyProductAbstractTableMap::COL_FK_TAX_SET,
                SpyTaxSetTableMap::COL_ID_TAX_SET,
                Criteria::INNER_JOIN
            )
            ->filterByFkTaxSet($idTaxSet)
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ]);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetForProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createTaxSetQuery()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * @api
     *
     * @deprecated Use queryTaxSetByIdProductAbstractAndCountryIso2Codes() instead.
     *
     * @module Country
     *
     * @param int[] $allIdProductAbstracts
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountryIso2Code(array $allIdProductAbstracts, $countryIso2Code)
    {
        return $this->getFactory()->createTaxSetQuery()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($allIdProductAbstracts, Criteria::IN)
                ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, self::COL_ID_ABSTRACT_PRODUCT)
                ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->endUse()
            ->useSpyTaxSetTaxQuery()
                ->useSpyTaxRateQuery()
                    ->useCountryQuery()
                        ->filterByIso2Code($countryIso2Code)
                    ->endUse()
                    ->_or()
                    ->filterByName(TaxConstants::TAX_EXEMPT_PLACEHOLDER)
                ->endUse()
                ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', self::COL_MAX_TAX_RATE)
            ->endUse()
            ->select([self::COL_MAX_TAX_RATE]);
    }

    /**
     * @api
     *
     * @module Country
     *
     * @param int[] $idProductAbstracts
     * @param string[] $countryIso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountryIso2Codes(array $idProductAbstracts, array $countryIso2Code): SpyTaxSetQuery
    {
        return $this->getFactory()
            ->createTaxSetQuery()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($idProductAbstracts, Criteria::IN)
                ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_ABSTRACT_PRODUCT)
                ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->endUse()
            ->useSpyTaxSetTaxQuery()
                ->useSpyTaxRateQuery()
                    ->useCountryQuery()
                        ->filterByIso2Code($countryIso2Code, Criteria::IN)
                        ->withColumn(SpyCountryTableMap::COL_ISO2_CODE, static::COL_COUNTRY_CODE)
                        ->groupBy(SpyCountryTableMap::COL_ISO2_CODE)
                    ->endUse()
                    ->_or()
                    ->filterByFkCountry(null)
                ->endUse()
                ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', static::COL_MAX_TAX_RATE)
            ->endUse()
            ->select([static::COL_COUNTRY_CODE, static::COL_MAX_TAX_RATE, SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);
    }
}
