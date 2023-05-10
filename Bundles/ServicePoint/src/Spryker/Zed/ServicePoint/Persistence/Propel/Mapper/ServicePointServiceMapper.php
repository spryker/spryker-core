<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ServicePointServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointServiceTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointService;
use Propel\Runtime\Collection\ObjectCollection;

class ServicePointServiceMapper
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceTransfer $servicePointServiceTransfer
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointService $servicePointServiceEntity
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointService
     */
    public function mapServicePointServiceTransferToServicePointServiceEntity(
        ServicePointServiceTransfer $servicePointServiceTransfer,
        SpyServicePointService $servicePointServiceEntity
    ): SpyServicePointService {
        $idServicePoint = $servicePointServiceTransfer->getServicePointOrFail()->getIdServicePointOrFail();
        $idServiceType = $servicePointServiceTransfer->getServiceTypeOrFail()->getIdServiceTypeOrFail();

        return $servicePointServiceEntity->fromArray($servicePointServiceTransfer->modifiedToArray())
            ->setFkServicePoint($idServicePoint)
            ->setFkServiceType($idServiceType);
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePointService $servicePointServiceEntity
     * @param \Generated\Shared\Transfer\ServicePointServiceTransfer $servicePointServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceTransfer
     */
    public function mapServicePointServiceEntityToServicePointServiceTransfer(
        SpyServicePointService $servicePointServiceEntity,
        ServicePointServiceTransfer $servicePointServiceTransfer
    ): ServicePointServiceTransfer {
        return $servicePointServiceTransfer->fromArray(
            $servicePointServiceEntity->toArray(),
            true,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePoint\Persistence\SpyServicePointService> $servicePointServiceEntities
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionTransfer $servicePointServiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionTransfer
     */
    public function mapServicePointServiceEntitiesToServicePointServiceCollectionTransfer(
        ObjectCollection $servicePointServiceEntities,
        ServicePointServiceCollectionTransfer $servicePointServiceCollectionTransfer
    ): ServicePointServiceCollectionTransfer {
        foreach ($servicePointServiceEntities as $servicePointServiceEntity) {
            $servicePointServiceTransfer = $this->mapServicePointServiceEntityToServicePointServiceTransfer(
                $servicePointServiceEntity,
                new ServicePointServiceTransfer(),
            );

            $servicePointServiceCollectionTransfer->addServicePointService($servicePointServiceTransfer);
        }

        return $servicePointServiceCollectionTransfer;
    }
}
