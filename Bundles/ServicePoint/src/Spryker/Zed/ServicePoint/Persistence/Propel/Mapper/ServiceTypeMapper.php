<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServiceType;
use Propel\Runtime\Collection\ObjectCollection;

class ServiceTypeMapper
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServiceType $serviceTypeEntity
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceType
     */
    public function mapServiceTypeTransferToServiceTypeEntity(
        ServiceTypeTransfer $serviceTypeTransfer,
        SpyServiceType $serviceTypeEntity
    ): SpyServiceType {
        return $serviceTypeEntity->fromArray(
            $serviceTypeTransfer->modifiedToArray(),
        );
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServiceType $serviceTypeEntity
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function mapServiceTypeEntityToServiceTypeTransfer(
        SpyServiceType $serviceTypeEntity,
        ServiceTypeTransfer $serviceTypeTransfer
    ): ServiceTypeTransfer {
        return $serviceTypeTransfer->fromArray(
            $serviceTypeEntity->toArray(),
            true,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePoint\Persistence\SpyServiceType> $serviceTypeEntities
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionTransfer $serviceTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionTransfer
     */
    public function mapServiceTypeEntitiesToServiceTypeCollectionTransfer(
        ObjectCollection $serviceTypeEntities,
        ServiceTypeCollectionTransfer $serviceTypeCollectionTransfer
    ): ServiceTypeCollectionTransfer {
        foreach ($serviceTypeEntities as $serviceTypeEntity) {
            $serviceTypeTransfer = $this->mapServiceTypeEntityToServiceTypeTransfer(
                $serviceTypeEntity,
                new ServiceTypeTransfer(),
            );

            $serviceTypeCollectionTransfer->addServiceType($serviceTypeTransfer);
        }

        return $serviceTypeCollectionTransfer;
    }
}
