<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointExpander implements ServicePointExpanderInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface
     */
    protected ServicePointRepositoryInterface $servicePointRepository;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     */
    public function __construct(ServicePointRepositoryInterface $servicePointRepository)
    {
        $this->servicePointRepository = $servicePointRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function expandServicePointAddressCollectionWithServicePointIds(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): ServicePointAddressCollectionTransfer {
        $servicePointUuids = $this->extractServicePointUuidsFromServicePointAddressCollection($servicePointAddressCollectionTransfer);
        $servicePointIdsIndexedByServicePointUuid = $this->servicePointRepository->getServicePointIdsIndexedByServicePointUuid($servicePointUuids);

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointAddressTransfer->getServicePointOrFail()->setIdServicePoint(
                $servicePointIdsIndexedByServicePointUuid[$servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail()],
            );
        }

        return $servicePointAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromServicePointAddressCollection(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): array {
        $servicePointUuids = [];

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointUuids[] = $servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $servicePointUuids;
    }
}
