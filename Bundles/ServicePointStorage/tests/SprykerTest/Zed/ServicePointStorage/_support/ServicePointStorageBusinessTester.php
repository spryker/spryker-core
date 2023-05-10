<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServicePointAddressBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePointStorage\Persistence\SpyServicePointStorageQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointStorageBusinessTester extends Actor
{
    use _generated\ServicePointStorageBusinessTesterActions;

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @param array<string, mixed> $servicePointSeedData
     * @param list<string> $storeNames
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransferWithStoreRelations(
        array $servicePointSeedData = [],
        array $storeNames = []
    ): ServicePointTransfer {
        $storesData = [];
        foreach ($storeNames as $storeName) {
            $storeTransfer = $this->haveStore((new StoreTransfer())->setName($storeName)->toArray());
            $storesData[] = $storeTransfer->toArray();
        }

        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->withStoreRelation([StoreRelationTransfer::STORES => $storesData])
            ->build();

        return $this->haveServicePoint($servicePointTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function createServicePointAddressTransfer(
        ServicePointTransfer $servicePointTransfer
    ): ServicePointAddressTransfer {
        $countryTransfer = $this->haveCountryTransfer();
        $regionTransfer = $this->haveRegion([
            RegionTransfer::FK_COUNTRY => $countryTransfer->getIdCountry(),
        ]);

        $servicePointAddressTransfer = (new ServicePointAddressBuilder())
            ->withCountry($countryTransfer->toArray())
            ->withRegion($regionTransfer->toArray())
            ->build();

        $servicePointAddressTransfer->setServicePoint($servicePointTransfer);

        return $this->haveServicePointAddress($servicePointAddressTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     * @param list<string> $storeNames
     *
     * @return void
     */
    public function createServicePointStorageByStoreRelations(
        ServicePointStorageTransfer $servicePointStorageTransfer,
        array $storeNames
    ): void {
        foreach ($storeNames as $storeName) {
            $servicePointStorageEntity = $this->getServicePointStorageQuery()
                ->filterByFkServicePoint($servicePointStorageTransfer->getIdServicePoint())
                ->filterByStore($storeName)
                ->findOneOrCreate();

            $servicePointStorageEntity->setData($servicePointStorageTransfer->toArray());
            $servicePointStorageEntity->save();
        }
    }

    /**
     * @param int $idServicePoint
     *
     * @return list<\Orm\Zed\ServicePointStorage\Persistence\Base\SpyServicePointStorage>
     */
    public function getServicePointStorageEntitiesByIdServicePoint(int $idServicePoint): array
    {
        return $this->getServicePointStorageQuery()
            ->filterByFkServicePoint($idServicePoint)
            ->find()
            ->getData();
    }

    /**
     * @return void
     */
    public function ensureServicePointStorageDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getServicePointStorageQuery());
    }

    /**
     * @return \Orm\Zed\ServicePointStorage\Persistence\SpyServicePointStorageQuery
     */
    protected function getServicePointStorageQuery(): SpyServicePointStorageQuery
    {
        return SpyServicePointStorageQuery::create();
    }
}
