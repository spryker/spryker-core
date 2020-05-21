<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Propel\Runtime\Collection\Collection;

class MerchantProductOfferMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $productOfferEntities
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function mapProductOfferEntityCollectionToProductOfferTransferCollection(Collection $productOfferEntities): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $this->mapTemplateEntityToTemplateTransfer($productOfferEntity, new ProductOfferTransfer());
            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntityTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function mapTemplateEntityToTemplateTransfer(
        SpyProductOffer $productOfferEntityTransfer,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        return $productOfferTransfer->fromArray($productOfferEntityTransfer->toArray(), true);
    }
}
