<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferReader;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface
     */
    protected $merchantProductOfferRepository;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface $merchantProductOfferRepository
     */
    public function __construct(
        MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferRepositoryInterface $merchantProductOfferRepository
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferRepository = $merchantProductOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getMerchantProductOfferCollection(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
    ): ProductOfferCollectionTransfer {
        $productOfferIds = $this->merchantProductOfferRepository->getProductOfferIds($merchantProductOfferCriteriaTransfer);

        if (!$productOfferIds) {
            return new ProductOfferCollectionTransfer();
        }

        $productOfferCriteriaTransfer = new ProductOfferCriteriaTransfer();
        $productOfferCriteriaTransfer->setProductOfferIds($productOfferIds);

        return $this->productOfferFacade->get($productOfferCriteriaTransfer);
    }
}
