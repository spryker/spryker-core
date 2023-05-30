<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyService;
use Propel\Runtime\Collection\ObjectCollection;

class ServiceMapper
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Orm\Zed\ServicePoint\Persistence\SpyService $serviceEntity
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyService
     */
    public function mapServiceTransferToServiceEntity(
        ServiceTransfer $serviceTransfer,
        SpyService $serviceEntity
    ): SpyService {
        $idServicePoint = $serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail();
        $idServiceType = $serviceTransfer->getServiceTypeOrFail()->getIdServiceTypeOrFail();

        return $serviceEntity->fromArray($serviceTransfer->modifiedToArray())
            ->setFkServicePoint($idServicePoint)
            ->setFkServiceType($idServiceType);
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyService $serviceEntity
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function mapServiceEntityToServiceTransfer(
        SpyService $serviceEntity,
        ServiceTransfer $serviceTransfer
    ): ServiceTransfer {
        $serviceTransfer->setServicePoint(
            (new ServicePointTransfer())->setUuid($serviceEntity->getServicePoint()->getUuid()),
        );

        $serviceTransfer->setServiceType(
            (new ServiceTypeTransfer())->setUuid($serviceEntity->getServiceType()->getUuid()),
        );

        return $serviceTransfer->fromArray(
            $serviceEntity->toArray(),
            true,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePoint\Persistence\SpyService> $serviceEntities
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function mapServiceEntitiesToServiceCollectionTransfer(
        ObjectCollection $serviceEntities,
        ServiceCollectionTransfer $serviceCollectionTransfer
    ): ServiceCollectionTransfer {
        foreach ($serviceEntities as $serviceEntity) {
            $serviceTransfer = $this->mapServiceEntityToServiceTransfer(
                $serviceEntity,
                new ServiceTransfer(),
            );

            $serviceCollectionTransfer->addService($serviceTransfer);
        }

        return $serviceCollectionTransfer;
    }
}
