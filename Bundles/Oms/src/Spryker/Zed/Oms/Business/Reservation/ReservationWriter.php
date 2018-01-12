<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class ReservationWriter implements ReservationWriterInterface
{
    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     */
    public function __construct(
        OmsToStoreFacadeInterface $storeFacade,
        OmsQueryContainerInterface $omsQueryContainer
    ) {
        $this->storeFacade = $storeFacade;
        $this->omsQueryContainer = $omsQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function saveReservationRequest(
        OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
    ) {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();

        $sku = $omsAvailabilityReservationRequestTransfer->getSku();
        $storeName = $omsAvailabilityReservationRequestTransfer->getOriginStore()->getName();

        if ($currentStoreTransfer->getName() !== $storeName) {
            return;
        }

        $reservationStoreEntity = $this->omsQueryContainer
            ->queryOmsProductReservationStoreBySkuForStore($sku, $storeName)
            ->findOneOrCreate();

        if ($this->isInvalidVersion($reservationStoreEntity, $omsAvailabilityReservationRequestTransfer)) {
            return;
        }

        $reservationStoreEntity->fromArray($omsAvailabilityReservationRequestTransfer->toArray());
        $reservationStoreEntity->setStore($storeName);
        $reservationStoreEntity->setReservationQuantity($omsAvailabilityReservationRequestTransfer->getReservationAmount());
        $reservationStoreEntity->save();
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore $reservationStoreEntity
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return bool
     */
    protected function isInvalidVersion(
        SpyOmsProductReservationStore $reservationStoreEntity,
        OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
    ) {
        return $reservationStoreEntity->isNew() || $reservationStoreEntity->getVersion() < $omsAvailabilityReservationRequestTransfer->getVersion() ? false : true;
    }
}
