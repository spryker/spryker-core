<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityOfferConnector\Business;

use Generated\Shared\Transfer\OfferTransfer;

interface AvailabilityOfferConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Add information about quote item stock to an offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOfferWithQuoteItemStock(OfferTransfer $offerTransfer): OfferTransfer;
}
