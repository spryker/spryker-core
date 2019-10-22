<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;

class ProductOfferMapper implements ProductOfferMapperInterface
{
    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $spyProductOffer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function mapProductOfferEntityToProductOfferTransfer(
        SpyProductOffer $spyProductOffer,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        $productOfferTransfer = $productOfferTransfer->fromArray(
            $spyProductOffer->toArray(),
            true
        );

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $spyProductOffer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOffer
     */
    public function mapProductOfferTransferToProductOfferEntity(
        ProductOfferTransfer $productOfferTransfer,
        SpyProductOffer $spyProductOffer
    ): SpyProductOffer {
        $spyProductOffer->fromArray(
            $productOfferTransfer->modifiedToArray(false)
        );

        return $spyProductOffer;
    }
}
