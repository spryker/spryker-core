<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferTransfer;

interface OfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer;

    /**
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(int $idOffer): OfferTransfer;
}
