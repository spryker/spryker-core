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
     *  - Store new offer to db.
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferTransfer;

    /**
     * Specification:
     *  - Update existing offer in db.
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferTransfer;
}
