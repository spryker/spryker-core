<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOffer\Zed;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Client\MerchantProductOffer\Dependency\Client\MerchantProductOfferToZedRequestClientInterface;

class MerchantProductOfferStub implements MerchantProductOfferStubInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOffer\Dependency\Client\MerchantProductOfferToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantProductOffer\Dependency\Client\MerchantProductOfferToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(MerchantProductOfferToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer */
        $productOfferCollectionTransfer = $this->zedRequestClient->call('/merchant-product-offer/gateway/get-product-offer-collection', $merchantProductOfferCriteriaFilterTransfer);

        return $productOfferCollectionTransfer;
    }
}
