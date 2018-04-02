<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use Generated\Shared\Transfer\OfferTransfer;

interface OfferEntityManagerInterface
{
    /**
     * Specification:
     *  - Change status of offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function saveOffer(OfferTransfer $offerTransfer): OfferTransfer;
}
