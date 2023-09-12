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
     * @var \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServiceTypeMapper
     */
    protected ServiceTypeMapper $serviceTypeMapper;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper
     */
    protected ServicePointMapper $servicePointMapper;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServiceTypeMapper $serviceTypeMapper
     * @param \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper $servicePointMapper
     */
    public function __construct(ServiceTypeMapper $serviceTypeMapper, ServicePointMapper $servicePointMapper)
    {
        $this->serviceTypeMapper = $serviceTypeMapper;
        $this->servicePointMapper = $servicePointMapper;
    }

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
        $serviceTransfer->setServicePoint($this->servicePointMapper->mapServicePointEntityToServicePointTransfer(
            $serviceEntity->getServicePoint(),
            new ServicePointTransfer(),
        ));

        $serviceTypeTransfer = $this->serviceTypeMapper->mapServiceTypeEntityToServiceTypeTransfer(
            $serviceEntity->getServiceType(),
            new ServiceTypeTransfer(),
        );
        $serviceTransfer->setServiceType($serviceTypeTransfer);

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
