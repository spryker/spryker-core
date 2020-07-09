<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Business;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Business\ProductOfferPackagingUnitBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitEntityManagerInterface getEntityManager()
 */
class ProductOfferPackagingUnitFacade extends AbstractFacade implements ProductOfferPackagingUnitFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        return $this->getRepository()->getAggregatedReservations($reservationRequestTransfer);
    }
}
