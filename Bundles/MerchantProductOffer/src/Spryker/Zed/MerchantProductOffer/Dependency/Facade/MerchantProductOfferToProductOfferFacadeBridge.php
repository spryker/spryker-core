<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;

class MerchantProductOfferToProductOfferFacadeBridge implements MerchantProductOfferToProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct($productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer
    {
        return $this->productOfferFacade->find($productOfferCriteriaFilter);
    }
}
