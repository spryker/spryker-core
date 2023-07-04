<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;

interface ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function expandProductOfferShipmentTypeCollectionWithShipmentTypes(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;
}
