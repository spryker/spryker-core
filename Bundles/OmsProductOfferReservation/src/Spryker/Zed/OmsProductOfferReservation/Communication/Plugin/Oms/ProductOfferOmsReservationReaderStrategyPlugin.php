<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface getFacade()
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 */
class ProductOfferOmsReservationReaderStrategyPlugin extends AbstractPlugin implements OmsReservationReaderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if ReservationRequest has productOfferReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return $reservationRequestTransfer->getProductOfferReference() !== null;
    }

    /**
     * {@inheritDoc}
     * - Returns ReservationResponse.reservationQuantity for provider product offer and store.
     * - Requires ReservationRequest.productOfferReference transfer field to be set.
     * - Requires ReservationRequest.store transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getReservationQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer
    {
        $omsProductOfferReservationCriteriaTransfer = (new OmsProductOfferReservationCriteriaTransfer())
            ->setProductOfferReference($reservationRequestTransfer->requireProductOfferReference()->getProductOfferReference())
            ->setStore($reservationRequestTransfer->requireStore()->getStore());

        return $this->getFacade()->getQuantity($omsProductOfferReservationCriteriaTransfer);
    }
}
