<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     */
    public function __construct(ProductOfferRepositoryInterface $productOfferRepository)
    {
        $this->productOfferRepository = $productOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer|null $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(?ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer
    {
        return $this->productOfferRepository->find($productOfferCriteriaFilter);
    }
}
