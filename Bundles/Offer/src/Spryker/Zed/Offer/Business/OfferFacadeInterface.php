<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OfferFacadeInterface
{
    /**
     * Specification:
     * - Return list of offers, using filter and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $offerList
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOfferList(OrderListTransfer $offerList): OrderListTransfer;

    /**
     * Specification:
     * - Set is_offer to false for an order and trigger state change
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $offer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(OrderTransfer $offer): OfferToOrderConvertResponseTransfer;
}
