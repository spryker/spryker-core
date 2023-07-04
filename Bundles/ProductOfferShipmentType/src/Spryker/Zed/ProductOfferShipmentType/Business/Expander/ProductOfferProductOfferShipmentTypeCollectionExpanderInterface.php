<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;

interface ProductOfferProductOfferShipmentTypeCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function expandProductOfferShipmentTypeCollectionWithProductOffers(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;
}
