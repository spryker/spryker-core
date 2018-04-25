<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Controller;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Offer\Business\OfferFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffersAction(OfferListTransfer $offerListTransfer)
    {
        return $this->getFacade()
            ->getOffers($offerListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferByIdAction(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->getFacade()->getOfferById($offerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOfferAction(OfferTransfer $offerTransfer)
    {
        return $this->getFacade()->createOffer($offerTransfer);
    }
}
