<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter;

use ArrayObject;

class ProductOfferStorageFilter implements ProductOfferStorageFilterInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ServiceStorageFilterInterface
     */
    protected ServiceStorageFilterInterface $serviceStorageFilter;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ServiceStorageFilterInterface $serviceStorageFilter
     */
    public function __construct(ServiceStorageFilterInterface $serviceStorageFilter)
    {
        $this->serviceStorageFilter = $serviceStorageFilter;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param list<string> $servicePointUuids
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function filterProductOfferStorageServicesByServicePointUuids(
        ArrayObject $productOfferStorageTransfers,
        array $servicePointUuids
    ): ArrayObject {
        $filteredProductOfferStorageTransfers = [];

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers */
            $serviceStorageTransfers = $productOfferStorageTransfer->getServices();
            $filteredServiceStorageTransfers = $this->serviceStorageFilter->filterServiceStoragesByServicePointUuids(
                $serviceStorageTransfers,
                $servicePointUuids,
            );

            if ($filteredServiceStorageTransfers) {
                $productOfferStorageTransfer->setServices(new ArrayObject($filteredServiceStorageTransfers));
                $filteredProductOfferStorageTransfers[] = $productOfferStorageTransfer;
            }
        }

        return new ArrayObject($filteredProductOfferStorageTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param string $serviceTypeUuid
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function filterProductOfferStorageServicesByServiceTypeUuid(
        ArrayObject $productOfferStorageTransfers,
        string $serviceTypeUuid
    ): ArrayObject {
        $filteredProductOfferStorageTransfers = [];

        /** @var \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer */
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers */
            $serviceStorageTransfers = $productOfferStorageTransfer->getServices();
            $filteredServiceStorageTransfers = $this->serviceStorageFilter->filterServiceStoragesByServiceTypeUuid(
                $serviceStorageTransfers,
                $serviceTypeUuid,
            );

            if ($filteredServiceStorageTransfers) {
                $productOfferStorageTransfer->setServices(new ArrayObject($filteredServiceStorageTransfers));
                $filteredProductOfferStorageTransfers[] = $productOfferStorageTransfer;
            }
        }

        return new ArrayObject($filteredProductOfferStorageTransfers);
    }
}
