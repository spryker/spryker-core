<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferReservationGui\Communication\Transformer;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface;

/**
 * @method \Spryker\Zed\ProductOfferReservationGui\ProductOfferReservationGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferReservationGui\Communication\ProductOfferReservationGuiCommunicationFactory getFactory()
 */
class ProductOfferReservationQuantityTransformer implements ProductOfferReservationQuantityTransformerInterface
{
 /**
  * @var \Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface
  */
    protected $productOfferReservationFacade;

    /**
     * @param \Spryker\Zed\ProductOfferReservationGui\Dependency\Facade\ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface $productOfferReservationFacade
     */
    public function __construct(
        ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface $productOfferReservationFacade
    ) {
        $this->productOfferReservationFacade = $productOfferReservationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return string
     */
    public function getReservationQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): string {
        $reservationQuantity = $this->productOfferReservationFacade
            ->getQuantity($omsProductOfferReservationCriteriaTransfer)
            ->getReservationQuantity();

        return $this->getTransformedStringRepresenation($reservationQuantity);
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $reservationQuantity
     *
     * @return string
     */
    protected function getTransformedStringRepresenation(Decimal $reservationQuantity): string
    {
        $intAsStringReservationQuantity = $reservationQuantity->trim()->toString();

        if ($intAsStringReservationQuantity === $reservationQuantity->toString()) {
            return $intAsStringReservationQuantity;
        }

        return $reservationQuantity->toString();
    }
}
