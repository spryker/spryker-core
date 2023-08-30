<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServiceStorageCollectionTransfer;

class ServicePointStorageExtractor implements ServicePointStorageExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceStorageCollectionTransfer
     */
    public function extractServiceStorageCollectionFromServicePointStorageCollectionTransfer(
        ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
    ): ServiceStorageCollectionTransfer {
        $serviceStorageCollectionTransfer = new ServiceStorageCollectionTransfer();
        $servicePointStorageTransfersWithoutServices = $this->extractServicePointTransfersWithoutServices($servicePointStorageCollectionTransfer);
        $servicePointStorageTransfersIndexedByUuid = $this->getServicePointTransfersIndexedByUuid($servicePointStorageTransfersWithoutServices);

        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            foreach ($servicePointStorageTransfer->getServices() as $serviceStorageTransfer) {
                $serviceStorageTransfer->setServicePoint($servicePointStorageTransfersIndexedByUuid[$servicePointStorageTransfer->getUuidOrFail()]);
                $serviceStorageCollectionTransfer->addService($serviceStorageTransfer);
            }
        }

        return $serviceStorageCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointStorageTransfer> $servicePointStorageTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointStorageTransfer>
     */
    protected function getServicePointTransfersIndexedByUuid(array $servicePointStorageTransfers): array
    {
        $servicePointStorageTransfersIndexedByUuid = [];

        foreach ($servicePointStorageTransfers as $servicePointStorageTransfer) {
            $servicePointStorageTransfersIndexedByUuid[$servicePointStorageTransfer->getUuidOrFail()] = $servicePointStorageTransfer;
        }

        return $servicePointStorageTransfersIndexedByUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return list<\Generated\Shared\Transfer\ServicePointStorageTransfer>
     */
    protected function extractServicePointTransfersWithoutServices(ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer): array
    {
        $servicePointStorageTransfers = [];

        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            $servicePointStorageTransfers[] = (new ServicePointStorageTransfer())
                ->fromArray($servicePointStorageTransfer->toArray(), true)
                ->setServices(new ArrayObject());
        }

        return $servicePointStorageTransfers;
    }
}
