<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability\Helper\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Spryker\DecimalObject\Decimal;

class ProductOfferOmsReservationReaderStrategyPluginForTesting
{
    /**
     * @var int
     */
    private int $quantityToReturn;

    /**
     * @param int $quantityToReturn
     */
    public function __construct(int $quantityToReturn)
    {
        $this->quantityToReturn = $quantityToReturn;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return $reservationRequestTransfer->getProductOfferReference() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getReservationQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer
    {
        return (new ReservationResponseTransfer())->setReservationQuantity(new Decimal($this->quantityToReturn));
    }
}
