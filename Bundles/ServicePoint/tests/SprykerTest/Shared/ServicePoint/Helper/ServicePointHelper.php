<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ServicePoint\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\ServiceBuilder;
use Generated\Shared\DataBuilder\ServicePointAddressBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\DataBuilder\ServiceTypeBuilder;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyService;
use Orm\Zed\ServicePoint\Persistence\SpyServicePoint;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddress;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStore;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceType;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ServicePointHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function haveServicePoint(array $seed = []): ServicePointTransfer
    {
        $servicePointTransfer = (new ServicePointBuilder($seed))->build();

        $servicePointEntity = (new SpyServicePoint())
            ->fromArray($servicePointTransfer->toArray());

        $servicePointEntity->save();
        $servicePointTransfer->fromArray($servicePointEntity->toArray(), true);

        if ($servicePointTransfer->getStoreRelation()) {
            $storeTransfers = [];

            foreach ($servicePointTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
                $storeTransfers[] = $this->createServicePointStore(
                    $servicePointEntity->getIdServicePoint(),
                    $storeTransfer->getIdStoreOrFail(),
                );
            }

            $servicePointTransfer->getStoreRelationOrFail()->setStores(
                new ArrayObject($storeTransfers),
            );
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($servicePointEntity): void {
            $this->deleteServicePoint($servicePointEntity->getIdServicePoint());
        });

        return $servicePointTransfer;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function haveServicePointAddress(array $seed = []): ServicePointAddressTransfer
    {
        $servicePointAddressTransfer = (new ServicePointAddressBuilder($seed))->build();

        $servicePointAddressEntity = (new SpyServicePointAddress())
            ->setFkServicePoint($servicePointAddressTransfer->getServicePointOrFail()->getIdServicePointOrFail())
            ->setFkCountry($servicePointAddressTransfer->getCountryOrFail()->getIdCountryOrFail())
            ->fromArray($servicePointAddressTransfer->modifiedToArray());

        if ($servicePointAddressTransfer->getRegion()) {
            $servicePointAddressEntity->setFkRegion($servicePointAddressTransfer->getRegionOrFail()->getIdRegionOrFail());
        }

        $servicePointAddressEntity->save();
        $servicePointAddressTransfer->fromArray($servicePointAddressEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($servicePointAddressEntity): void {
            $this->deleteServicePointAddress($servicePointAddressEntity->getIdServicePointAddress());
        });

        return $servicePointAddressTransfer;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function haveServiceType(array $seed = []): ServiceTypeTransfer
    {
        $serviceTypeTransfer = (new ServiceTypeBuilder($seed))->build();

        $serviceTypeEntity = (new SpyServiceType())
            ->fromArray($serviceTypeTransfer->toArray());

        $serviceTypeEntity->save();

        $serviceTypeTransfer->fromArray(
            $serviceTypeEntity->toArray(),
            true,
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($serviceTypeEntity): void {
            $this->deleteServiceType($serviceTypeEntity->getIdServiceType());
        });

        return $serviceTypeTransfer;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function haveService(array $seed = []): ServiceTransfer
    {
        $serviceTypeTransfer = $this->haveServiceType($seed[ServiceTransfer::SERVICE_TYPE] ?? []);
        $servicePointTransfer = $this->haveServicePoint($seed[ServiceTransfer::SERVICE_POINT] ?? []);

        $serviceTransfer = (new ServiceBuilder($seed))
            ->withServicePoint($servicePointTransfer->toArray())
            ->withServiceType($serviceTypeTransfer->toArray())
            ->build();

        $serviceEntity = (new SpyService())
            ->fromArray($serviceTransfer->toArray())
            ->setFkServicePoint($servicePointTransfer->getIdServicePointOrFail())
            ->setFkServiceType($serviceTypeTransfer->getIdServiceTypeOrFail());

        $serviceEntity->save();

        $serviceTransfer->fromArray(
            $serviceEntity->toArray(),
            true,
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($serviceEntity): void {
            $this->deleteService($serviceEntity->getIdService());
        });

        return $serviceTransfer;
    }

    /**
     * @param int $idServicePoint
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createServicePointStore(
        int $idServicePoint,
        int $idStore
    ): StoreTransfer {
        $servicePointStoreEntity = (new SpyServicePointStore())
            ->setFkServicePoint($idServicePoint)
            ->setFkStore($idStore);

        $servicePointStoreEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($servicePointStoreEntity): void {
            $this->deleteServicePointStore($servicePointStoreEntity->getIdServicePointStore());
        });

        $storeEntity = $servicePointStoreEntity->getStore();

        return (new StoreTransfer())->fromArray(
            $storeEntity->toArray(),
            true,
        );
    }

    /**
     * @param int $idServicePoint
     *
     * @return void
     */
    protected function deleteServicePoint(int $idServicePoint): void
    {
        $servicePointEntity = $this->getServicePointQuery()->findOneByIdServicePoint($idServicePoint);

        if ($servicePointEntity) {
            $servicePointEntity->delete();
        }
    }

    /**
     * @param int $idServicePointAddress
     *
     * @return void
     */
    protected function deleteServicePointAddress(int $idServicePointAddress): void
    {
        $servicePointAddressEntity = $this->getServicePointAddressQuery()->findOneByIdServicePointAddress($idServicePointAddress);

        if ($servicePointAddressEntity) {
            $servicePointAddressEntity->delete();
        }
    }

    /**
     * @param int $idServicePointStore
     *
     * @return void
     */
    protected function deleteServicePointStore(int $idServicePointStore): void
    {
        $servicePointStoreEntity = $this->getServicePointStoreQuery()->findOneByIdServicePointStore($idServicePointStore);

        if ($servicePointStoreEntity) {
            $servicePointStoreEntity->delete();
        }
    }

    /**
     * @param int $idServiceType
     *
     * @return void
     */
    protected function deleteServiceType(int $idServiceType): void
    {
        $serviceTypeEntity = $this->getServiceTypeQuery()
            ->findOneByIdServiceType($idServiceType);

        if ($serviceTypeEntity) {
            $serviceTypeEntity->delete();
        }
    }

    /**
     * @param int $idService
     *
     * @return void
     */
    protected function deleteService(int $idService): void
    {
        $serviceEntity = $this->getServiceQuery()
            ->findOneByIdService($idService);

        if ($serviceEntity) {
            $serviceEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery
     */
    protected function getServicePointAddressQuery(): SpyServicePointAddressQuery
    {
        return SpyServicePointAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery
     */
    protected function getServicePointStoreQuery(): SpyServicePointStoreQuery
    {
        return SpyServicePointStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    protected function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }
}
