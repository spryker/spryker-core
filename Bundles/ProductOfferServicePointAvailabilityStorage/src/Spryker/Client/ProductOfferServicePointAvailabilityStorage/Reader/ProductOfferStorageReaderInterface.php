<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;

interface ProductOfferStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOfferStorageTransfersByProductOfferServicePointAvailabilityConditions(
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): ArrayObject;
}
