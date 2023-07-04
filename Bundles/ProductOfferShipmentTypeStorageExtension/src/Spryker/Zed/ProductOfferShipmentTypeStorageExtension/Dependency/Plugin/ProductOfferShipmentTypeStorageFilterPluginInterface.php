<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;

/**
 * Provides ability to filter product offer shipment type collection transfer by provided criteria.
 */
interface ProductOfferShipmentTypeStorageFilterPluginInterface
{
    /**
     * Specification:
     * - Filters `ProductOfferShipmentTypeCollectionTransfer.productOfferShipmentTypes` transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function filter(ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer): ProductOfferShipmentTypeCollectionTransfer;
}
