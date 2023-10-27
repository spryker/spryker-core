<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter;

use ArrayObject;

class ServiceStorageFilter implements ServiceStorageFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers
     * @param list<string> $servicePointUuids
     *
     * @return list<\Generated\Shared\Transfer\ServiceStorageTransfer>
     */
    public function filterServiceStoragesByServicePointUuids(ArrayObject $serviceStorageTransfers, array $servicePointUuids): array
    {
        $filteredServiceStorageTransfers = [];

        foreach ($serviceStorageTransfers as $serviceStorageTransfer) {
            if (!in_array($serviceStorageTransfer->getServicePointOrFail()->getUuidOrFail(), $servicePointUuids, true)) {
                continue;
            }

            $filteredServiceStorageTransfers[] = $serviceStorageTransfer;
        }

        return $filteredServiceStorageTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers
     * @param string $serviceTypeUuid
     *
     * @return list<\Generated\Shared\Transfer\ServiceStorageTransfer>
     */
    public function filterServiceStoragesByServiceTypeUuid(ArrayObject $serviceStorageTransfers, string $serviceTypeUuid): array
    {
        $filteredServiceStorageTransfers = [];

        foreach ($serviceStorageTransfers as $serviceStorageTransfer) {
            if ($serviceStorageTransfer->getServiceTypeOrFail()->getUuidOrFail() !== $serviceTypeUuid) {
                continue;
            }

            $filteredServiceStorageTransfers[] = $serviceStorageTransfer;
        }

        return $filteredServiceStorageTransfers;
    }
}
