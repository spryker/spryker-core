<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence\Mapper;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\SpyOfferEntityTransfer;

interface OfferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function mapOfferEntityToOffer(SpyOfferEntityTransfer $offerEntityTransfer): OfferTransfer;

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOfferEntityTransfer
     */
    public function mapOfferToOfferEntity(OfferTransfer $offerTransfer): SpyOfferEntityTransfer;
}
