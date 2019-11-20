<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;

interface ProductOfferMapperInterface
{
    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function mapProductOfferEntityToProductOfferTransfer(
        SpyProductOffer $productOfferEntity,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOffer
     */
    public function mapProductOfferTransferToProductOfferEntity(
        ProductOfferTransfer $productOfferTransfer,
        SpyProductOffer $productOfferEntity
    ): SpyProductOffer;
}
