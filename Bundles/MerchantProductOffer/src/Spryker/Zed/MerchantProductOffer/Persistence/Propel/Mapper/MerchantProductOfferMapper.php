<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Propel\Runtime\Collection\Collection;

class MerchantProductOfferMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $productOfferEntities
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function mapProductOfferEntityCollectionToProductOfferTransferCollection(
        Collection $productOfferEntities,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = (new ProductOfferTransfer())->fromArray($productOfferEntity->toArray(), true);
            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }
}
