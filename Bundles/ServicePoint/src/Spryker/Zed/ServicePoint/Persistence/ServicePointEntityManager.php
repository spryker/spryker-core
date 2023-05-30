<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyService;
use Orm\Zed\ServicePoint\Persistence\SpyServicePoint;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddress;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStore;
use Orm\Zed\ServicePoint\Persistence\SpyServiceType;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointPersistenceFactory getFactory()
 */
class ServicePointEntityManager extends AbstractEntityManager implements ServicePointEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePoint(ServicePointTransfer $servicePointTransfer): ServicePointTransfer
    {
        $servicePointEntity = $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointTransferToServicePointEntity($servicePointTransfer, new SpyServicePoint());

        $servicePointEntity->save();

        return $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointEntityToServicePointTransfer($servicePointEntity, $servicePointTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function createServicePointAddress(ServicePointAddressTransfer $servicePointAddressTransfer): ServicePointAddressTransfer
    {
        $servicePointAddressEntity = $this->getFactory()
            ->createServicePointAddressMapper()
            ->mapServicePointAddressTransferToServicePointAddressEntity($servicePointAddressTransfer, new SpyServicePointAddress());

        $servicePointAddressEntity->save();

        return $this->getFactory()
            ->createServicePointAddressMapper()
            ->mapServicePointAddressEntityToServicePointAddressTransfer($servicePointAddressEntity, $servicePointAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function updateServicePoint(ServicePointTransfer $servicePointTransfer): ServicePointTransfer
    {
        $servicePointEntity = $this->getFactory()
            ->getServicePointQuery()
            ->filterByUuid($servicePointTransfer->getUuidOrFail())
            ->findOne();

        if (!$servicePointEntity) {
            return $servicePointTransfer;
        }

        $servicePointEntity = $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointTransferToServicePointEntity($servicePointTransfer, $servicePointEntity);

        $servicePointEntity->save();

        return $this->getFactory()
            ->createServicePointMapper()
            ->mapServicePointEntityToServicePointTransfer($servicePointEntity, $servicePointTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function updateServicePointAddress(ServicePointAddressTransfer $servicePointAddressTransfer): ServicePointAddressTransfer
    {
        $servicePointAddressEntity = $this->getFactory()
            ->getServicePointAddressQuery()
            ->filterByUuid($servicePointAddressTransfer->getUuidOrFail())
            ->findOne();

        if (!$servicePointAddressEntity) {
            return $servicePointAddressTransfer;
        }

        $servicePointAddressEntity = $this->getFactory()
            ->createServicePointAddressMapper()
            ->mapServicePointAddressTransferToServicePointAddressEntity($servicePointAddressTransfer, $servicePointAddressEntity);

        $servicePointAddressEntity->save();

        return $this->getFactory()
            ->createServicePointAddressMapper()
            ->mapServicePointAddressEntityToServicePointAddressTransfer($servicePointAddressEntity, $servicePointAddressTransfer);
    }

    /**
     * @param int $idServicePoint
     * @param array<int, int> $storeIds
     *
     * @return void
     */
    public function createServicePointStores(int $idServicePoint, array $storeIds): void
    {
        foreach ($storeIds as $idStore) {
            (new SpyServicePointStore())
                ->setFkServicePoint($idServicePoint)
                ->setFkStore($idStore)
                ->save();
        }
    }

    /**
     * @param int $idServicePoint
     * @param array<int, int> $storeIds
     *
     * @return void
     */
    public function deleteServicePointStores(int $idServicePoint, array $storeIds): void
    {
        $this->getFactory()
            ->getServicePointStoreQuery()
            ->filterByFkServicePoint($idServicePoint)
            ->filterByFkStore_In($storeIds)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function createService(ServiceTransfer $serviceTransfer): ServiceTransfer
    {
        $serviceMapper = $this->getFactory()->createServiceMapper();
        $serviceEntity = $serviceMapper->mapServiceTransferToServiceEntity(
            $serviceTransfer,
            new SpyService(),
        );

        $serviceEntity->save();

        return $serviceMapper->mapServiceEntityToServiceTransfer(
            $serviceEntity,
            $serviceTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function updateService(ServiceTransfer $serviceTransfer): ServiceTransfer
    {
        $serviceMapper = $this->getFactory()->createServiceMapper();
        $serviceEntity = $this->getFactory()
            ->getServiceQuery()
            ->filterByUuid($serviceTransfer->getUuidOrFail())
            ->findOne();

        if (!$serviceEntity) {
            return $serviceTransfer;
        }

        $serviceEntity->fromArray($serviceTransfer->modifiedToArray());
        $serviceEntity->save();

        return $serviceMapper->mapServiceEntityToServiceTransfer(
            $serviceEntity,
            $serviceTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function createServiceType(ServiceTypeTransfer $serviceTypeTransfer): ServiceTypeTransfer
    {
        $serviceTypeMapper = $this->getFactory()->createServiceTypeMapper();
        $serviceTypeEntity = $serviceTypeMapper->mapServiceTypeTransferToServiceTypeEntity(
            $serviceTypeTransfer,
            new SpyServiceType(),
        );

        $serviceTypeEntity->save();

        return $serviceTypeMapper->mapServiceTypeEntityToServiceTypeTransfer(
            $serviceTypeEntity,
            $serviceTypeTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function updateServiceType(ServiceTypeTransfer $serviceTypeTransfer): ServiceTypeTransfer
    {
        $serviceTypeMapper = $this->getFactory()->createServiceTypeMapper();
        $serviceTypeEntity = $this->getFactory()
            ->getServiceTypeQuery()
            ->filterByUuid($serviceTypeTransfer->getUuidOrFail())
            ->findOne();

        if (!$serviceTypeEntity) {
            return $serviceTypeTransfer;
        }

        $serviceTypeEntity = $serviceTypeMapper->mapServiceTypeTransferToServiceTypeEntity(
            $serviceTypeTransfer,
            $serviceTypeEntity,
        );

        $serviceTypeEntity->save();

        return $serviceTypeMapper->mapServiceTypeEntityToServiceTypeTransfer(
            $serviceTypeEntity,
            $serviceTypeTransfer,
        );
    }
}
