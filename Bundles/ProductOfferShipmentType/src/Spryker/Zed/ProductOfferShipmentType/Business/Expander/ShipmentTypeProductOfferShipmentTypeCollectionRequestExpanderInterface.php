<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;

interface ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer
     */
    public function expandWithShipmentTypeIds(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionRequestTransfer;
}
