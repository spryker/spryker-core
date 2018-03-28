<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Offer\Business\OfferBusinessFactory getFactory()
 */
class OfferFacade extends AbstractFacade implements OfferFacadeInterface
{
    //TODO: remove method?.
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffers(OrderListTransfer $offerListTransfer): OrderListTransfer
    {
        return $this->getFactory()
            ->createOfferReader()
            ->getOfferList($offerListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(int $idSalesOrder): OfferToOrderConvertResponseTransfer
    {
        return $this->getFactory()
            ->createOfferConverter()
            ->convertToOrder($idSalesOrder);
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->getFactory()
            ->createOfferWriter()
            ->placeOffer($offerTransfer);
    }
}
