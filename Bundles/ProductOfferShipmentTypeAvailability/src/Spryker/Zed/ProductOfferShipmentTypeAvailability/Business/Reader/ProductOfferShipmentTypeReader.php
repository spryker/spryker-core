<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface;

class ProductOfferShipmentTypeReader implements ProductOfferShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface
     */
    protected ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade
     */
    public function __construct(ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade)
    {
        $this->productOfferShipmentTypeFacade = $productOfferShipmentTypeFacade;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollectionByProductOfferIds(array $productOfferIds): ProductOfferShipmentTypeCollectionTransfer
    {
        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
            ->setProductOfferShipmentTypeConditions(
                (new ProductOfferShipmentTypeConditionsTransfer())
                    ->setGroupByIdProductOffer(true)
                    ->setProductOfferIds($productOfferIds),
            );

        return $this->productOfferShipmentTypeFacade->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);
    }
}
