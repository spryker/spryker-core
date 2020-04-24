<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservationQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationDependencyProvider;
use Spryker\Zed\OmsProductOfferReservation\Persistence\Mapper\OmsProductOfferReservationMapper;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface getRepository()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface getEntityManager()
 */
class OmsProductOfferReservationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservationQuery
     */
    public function getOmsProductOfferReservationPropelQuery(): SpyOmsProductOfferReservationQuery
    {
        return SpyOmsProductOfferReservationQuery::create();
    }

    /**
     * @return \Spryker\Zed\OmsProductOfferReservation\Persistence\Mapper\OmsProductOfferReservationMapper
     */
    public function createOmsProductOfferReservationMapper(): OmsProductOfferReservationMapper
    {
        return new OmsProductOfferReservationMapper();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(OmsProductOfferReservationDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
