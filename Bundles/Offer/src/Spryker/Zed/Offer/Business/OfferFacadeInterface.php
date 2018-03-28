<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OfferTransfer;

interface OfferFacadeInterface
{
    /**
     * todo: remove
     * @deprecated make getOffersByCustomerReference
     *
     * Specification:
     * - Return list of offers, using filter and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $offerList
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffers(OrderListTransfer $offerList): OrderListTransfer;

    /**
     * Specification:
     *  - Set is_offer flag to false for offer to make it a usual order.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(int $idSalesOrder): OfferToOrderConvertResponseTransfer;

    /**
     *todo:
     * @api
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;


    /**
     * todo:
     * Specification:
     * -
     *
     * @api
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer;
}
