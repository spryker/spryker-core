<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface;

class ProductOfferServiceReader implements ProductOfferServiceReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface
     */
    protected ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface $productOfferServicePointFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     */
    public function __construct(ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface $productOfferServicePointFacade)
    {
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollectionByProductOfferIds(array $productOfferIds): ProductOfferServiceCollectionTransfer
    {
        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
            ->setProductOfferServiceConditions(
                (new ProductOfferServiceConditionsTransfer())
                    ->setWithServicePointRelations(true)
                    ->setGroupByIdProductOffer(true)
                    ->setProductOfferIds($productOfferIds),
            );

        return $this->productOfferServicePointFacade->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
    }
}
