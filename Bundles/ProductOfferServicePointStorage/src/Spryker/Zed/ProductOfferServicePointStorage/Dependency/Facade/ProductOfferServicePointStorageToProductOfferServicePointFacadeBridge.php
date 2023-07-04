<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;

class ProductOfferServicePointStorageToProductOfferServicePointFacadeBridge implements ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface
     */
    protected $productOfferServicePointFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     */
    public function __construct($productOfferServicePointFacade)
    {
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollection(
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        return $this->productOfferServicePointFacade->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return iterable<list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>>
     */
    public function iterateProductOfferServices(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): iterable {
        return $this->productOfferServicePointFacade->iterateProductOfferServices($iterableProductOfferServicesCriteriaTransfer);
    }
}
