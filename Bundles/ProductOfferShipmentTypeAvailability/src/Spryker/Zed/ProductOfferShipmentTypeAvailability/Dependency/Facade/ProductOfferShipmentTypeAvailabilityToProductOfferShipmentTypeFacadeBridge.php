<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;

class ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeBridge implements ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface
     */
    protected $productOfferShipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade
     */
    public function __construct($productOfferShipmentTypeFacade)
    {
        $this->productOfferShipmentTypeFacade = $productOfferShipmentTypeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        return $this->productOfferShipmentTypeFacade->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);
    }
}
