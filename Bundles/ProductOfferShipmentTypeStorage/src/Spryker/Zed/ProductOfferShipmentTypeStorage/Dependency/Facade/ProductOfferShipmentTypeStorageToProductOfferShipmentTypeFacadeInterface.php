<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;

interface ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypesIterator(
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): iterable;
}
