<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxProductConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class TaxProductConnectorQueryContainer extends AbstractQueryContainer implements TaxProductConnectorQueryContainerInterface
{

    /**
     * @param int $idTaxRate
     *
     * @return SpyAbstractProductQuery
     */
    public function getAbstractProductIdsForTaxRate($idTaxRate)
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
            ])
            ->useSpyTaxSetQuery()
                ->useSpyTaxSetTaxQuery()
                    ->useSpyTaxRateQuery()
                    ->filterByIdTaxRate($idTaxRate)
                    ->endUse()
                ->endUse()
            ->endUse()
            ;
    }

    /**
     * @param int $idTaxSet
     *
     * @return SpyAbstractProductQuery
     */
    public function getAbstractProductIdsForTaxSet($idTaxSet)
    {
        return SpyAbstractProductQuery::create()
            ->addJoin(
                SpyAbstractProductTableMap::COL_FK_TAX_SET,
                SpyTaxSetTableMap::COL_ID_TAX_SET,
                Criteria::INNER_JOIN
            )
            ->filterByFkTaxSet($idTaxSet)
            ->select([
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
            ])
            ;
    }

}
