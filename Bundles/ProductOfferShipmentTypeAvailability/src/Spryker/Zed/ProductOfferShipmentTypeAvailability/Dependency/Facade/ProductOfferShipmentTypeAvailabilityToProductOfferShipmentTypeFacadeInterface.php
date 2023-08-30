<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;

interface ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;
}
