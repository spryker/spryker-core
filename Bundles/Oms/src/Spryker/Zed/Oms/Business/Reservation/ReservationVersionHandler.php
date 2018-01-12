<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class ReservationVersionHandler implements ReservationVersionHandlerInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     */
    public function __construct(OmsQueryContainerInterface $omsQueryContainer)
    {
        $this->omsQueryContainer = $omsQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function saveReservationVersion($sku)
    {
        $idOmsProductReservation = $this->omsQueryContainer
            ->createOmsProductReservationQuery($sku)
            ->select([SpyOmsProductReservationTableMap::COL_ID_OMS_PRODUCT_RESERVATION])
            ->findOne();

        (new SpyOmsProductReservationChangeVersion())
            ->setIdOmsProductReservationId($idOmsProductReservation)
            ->save();
    }
}
