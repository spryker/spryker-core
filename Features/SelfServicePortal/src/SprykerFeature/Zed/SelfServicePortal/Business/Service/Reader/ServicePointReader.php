<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader;

use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @param \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(protected ServicePointFacadeInterface $servicePointFacade)
    {
    }

    /**
     * @param array<string> $servicePointUuids
     * @param string $storeName
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function getServicePointsIndexedByUuids(array $servicePointUuids, string $storeName): array
    {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setUuids($servicePointUuids)
            ->setStoreNames([$storeName])
            ->setWithAddressRelation(true)
            ->setIsActive(true);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        $servicePointTransfersByUuid = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $servicePointTransfersByUuid[$servicePointTransfer->getUuidOrFail()] = $servicePointTransfer;
        }

        return $servicePointTransfersByUuid;
    }
}
