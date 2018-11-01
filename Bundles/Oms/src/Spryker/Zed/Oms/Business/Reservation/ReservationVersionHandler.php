<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class ReservationVersionHandler implements ReservationVersionHandlerInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        OmsQueryContainerInterface $omsQueryContainer,
        OmsToStoreFacadeInterface $storeFacade
    ) {
        $this->omsQueryContainer = $omsQueryContainer;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function saveReservationVersion($sku)
    {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();

        $idOmsProductReservation = $this->omsQueryContainer
            ->queryProductReservationBySkuAndStore($sku, $currentStoreTransfer->getIdStore())
            ->select([SpyOmsProductReservationTableMap::COL_ID_OMS_PRODUCT_RESERVATION])
            ->findOne();

        (new SpyOmsProductReservationChangeVersion())
            ->setIdOmsProductReservationId($idOmsProductReservation)
            ->save();
    }
}
