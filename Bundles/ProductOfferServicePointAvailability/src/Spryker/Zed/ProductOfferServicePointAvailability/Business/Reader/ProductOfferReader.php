<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeInterface
     */
    protected ProductOfferServicePointAvailabilityToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferServicePointAvailabilityToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferCollectionTransfer
    {
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferConditions(
                (new ProductOfferConditionsTransfer())->setProductOfferReferences($productOfferReferences),
            );

        return $this->productOfferFacade->getProductOfferCollection($productOfferCriteriaTransfer);
    }
}
