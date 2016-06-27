<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentQueryContainer extends AbstractQueryContainer implements ShipmentQueryContainerInterface
{

    const COL_SUM_TAX_RATE = 'SumTaxRate';

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryCarriers()
    {
        return $this->getFactory()->createShipmentCarrierQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers()
    {
        return $this->queryCarriers()->filterByIsActive(true);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethods()
    {
        return $this->getFactory()->createShipmentMethodQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethods()
    {
        return $this->queryMethods()->filterByIsActive(true);
    }

    /**
     * @api
     *
     * @param int $idMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod)
    {
        $query = $this->queryMethods();
        $query->filterByIdShipmentMethod($idMethod);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idShipmentMethod
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByIdShipmentMethodAndCountry($idShipmentMethod, $iso2Code)
    {
        return $this->getFactory()->createShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->useTaxSetQuery()
                ->useSpyTaxSetTaxQuery()
                    ->useSpyTaxRateQuery()
                        ->useCountryQuery()
                            ->filterByIso2Code($iso2Code)
                        ->endUse()
                        ->_or()
                        ->filterByName(TaxConstants::TAX_EXEMPT_PLACEHOLDER)
                    ->endUse()
                ->endUse()
                ->withColumn(SpyTaxSetTableMap::COL_NAME)
                ->groupBy(SpyTaxSetTableMap::COL_NAME)
                ->withColumn('SUM(' . SpyTaxRateTableMap::COL_RATE . ')', self::COL_SUM_TAX_RATE)
            ->endUse()
            ->select([self::COL_SUM_TAX_RATE]);
    }

}
