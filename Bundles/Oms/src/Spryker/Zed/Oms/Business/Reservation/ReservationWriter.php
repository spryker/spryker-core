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

        $sku = $omsAvailabilityReservationRequestTransfer->getSku();
        $originStoreName = $omsAvailabilityReservationRequestTransfer->getOriginStore()->getName();

        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        if ($currentStoreTransfer->getName() === $originStoreName) {
            return;
        }

        $reservationStoreEntity = $this->findReservationStoreEntity($sku, $originStoreName);

        if ($this->isInvalidVersion($reservationStoreEntity, $omsAvailabilityReservationRequestTransfer)) {
            return;
        }

        $this->saveReservationStoreEntity($omsAvailabilityReservationRequestTransfer, $reservationStoreEntity, $originStoreName);
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

    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     * @param \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore $reservationStoreEntity
     * @param string $originStoreName
     *
     * @return void
     */
    protected function saveReservationStoreEntity(
        OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer,
        SpyOmsProductReservationStore $reservationStoreEntity,
        $originStoreName
    ) {
        $reservationStoreEntity->fromArray($omsAvailabilityReservationRequestTransfer->toArray());
        $reservationStoreEntity->setStore($originStoreName);
        $reservationStoreEntity->setReservationQuantity($omsAvailabilityReservationRequestTransfer->getReservationAmount());
        $reservationStoreEntity->save();
    }

    /**
     * @param string $sku
     * @param string $storeName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore
     */
    protected function findReservationStoreEntity($sku, $storeName)
    {
        return $this->omsQueryContainer
            ->queryOmsProductReservationStoreBySkuForStore($sku, $storeName)
            ->findOneOrCreate();
    }
}
