<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter;

use ArrayObject;

interface ProductOfferStorageFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param list<string> $servicePointUuids
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function filterProductOfferStorageServicesByServicePointUuids(
        ArrayObject $productOfferStorageTransfers,
        array $servicePointUuids
    ): ArrayObject;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param string $serviceTypeUuid
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function filterProductOfferStorageServicesByServiceTypeUuid(
        ArrayObject $productOfferStorageTransfers,
        string $serviceTypeUuid
    ): ArrayObject;
}
