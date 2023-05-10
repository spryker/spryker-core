<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointServiceServiceTypeExpander implements ServicePointServiceServiceTypeExpanderInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    public function expandServicePointServiceTransfersWithServiceTypeRelations(
        ArrayObject $servicePointServiceTransfers
    ): ArrayObject {
        $serviceTypeUuids = $this->extractServiceTypeUuidsFromServicePointServiceTransfers($servicePointServiceTransfers);
        $serviceTypeTransfersIndexedByUuids = $this->getServiceTypeTransfersIndexedByUuids($serviceTypeUuids);

        foreach ($servicePointServiceTransfers as $servicePointServiceTransfer) {
            $serviceTypeUuid = $servicePointServiceTransfer->getServiceTypeOrFail()->getUuidOrFail();

            $servicePointServiceTransfer->setServiceType(
                $serviceTypeTransfersIndexedByUuids[$serviceTypeUuid],
            );
        }

        return $servicePointServiceTransfers;
    }

    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return array<string, \Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    protected function getServiceTypeTransfersIndexedByUuids(array $serviceTypeUuids): array
    {
        $serviceTypeTransfersIndexedByUuids = [];
        $serviceTypeCollectionTransfer = $this->getServiceTypeCollectionTransfer($serviceTypeUuids);

        foreach ($serviceTypeCollectionTransfer->getServiceTypes() as $serviceTypeTransfer) {
            $serviceTypeTransfersIndexedByUuids[$serviceTypeTransfer->getUuidOrFail()] = $serviceTypeTransfer;
        }

        return $serviceTypeTransfersIndexedByUuids;
    }

    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionTransfer
     */
    protected function getServiceTypeCollectionTransfer(
        array $serviceTypeUuids
    ): ServiceTypeCollectionTransfer {
        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())->setUuids($serviceTypeUuids);
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())->setServiceTypeConditions($serviceTypeConditionsTransfer);

        return $this->servicePointRepository->getServiceTypeCollection($serviceTypeCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return list<string>
     */
    protected function extractServiceTypeUuidsFromServicePointServiceTransfers(
        ArrayObject $servicePointServiceTransfers
    ): array {
        $serviceTypeUuids = [];

        foreach ($servicePointServiceTransfers as $servicePointServiceTransfer) {
            $serviceTypeUuids[] = $servicePointServiceTransfer->getServiceTypeOrFail()->getUuidOrFail();
        }

        return $serviceTypeUuids;
    }
}
