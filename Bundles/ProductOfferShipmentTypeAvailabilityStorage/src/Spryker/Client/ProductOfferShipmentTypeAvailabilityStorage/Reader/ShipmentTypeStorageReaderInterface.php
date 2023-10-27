<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;

interface ShipmentTypeStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer|null
     */
    public function findShipmentTypeStorageByProductOfferServicePointAvailabilityConditionsTransfer(
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): ?ShipmentTypeStorageTransfer;
}
