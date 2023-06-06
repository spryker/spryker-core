<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferShipmentTypeFacadeInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Expands `ProductOfferTransfer` with related shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ShipmentTypeTransfer.shipmentTypeUuid` to be set for each `ShipmentTypeTransfer` in `ProductOfferTransfer.shipmentTypes` collection.
     * - Iterates over `ProductOfferTransfer.shipmentTypes`.
     * - Persists product offer shipment types to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ShipmentTypeTransfer.shipmentTypeUuid` to be set for each `ShipmentTypeTransfer` in `ProductOfferTransfer.shipmentTypes` collection.
     * - Deletes redundant product offer shipment types from Persistence.
     * - Persists missed product offer shipment types to Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function updateProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
