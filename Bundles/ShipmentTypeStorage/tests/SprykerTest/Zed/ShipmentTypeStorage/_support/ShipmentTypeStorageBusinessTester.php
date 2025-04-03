<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage;

use Codeception\Actor;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeListStorageQuery;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorage;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Zed\ShipmentType\Communication\Plugin\Shipment\ShipmentTypeShipmentMethodCollectionExpanderPlugin;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface;
use Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentTypeStorage\PHPMD)
 */
class ShipmentTypeStorageBusinessTester extends Actor
{
    use _generated\ShipmentTypeStorageBusinessTesterActions;

    /**
     * @uses \Spryker\Client\Queue\QueueDependencyProvider::QUEUE_ADAPTERS
     *
     * @var string
     */
    protected const QUEUE_ADAPTERS = 'queue adapters';

    /**
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'FkShipmentType';

    /**
     * @uses \Spryker\Zed\Shipment\ShipmentDependencyProvider::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER
     *
     * @var string
     */
    protected const PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER = 'PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER';

    /**
     * @param int $idShipmentType
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer|null
     */
    public function findShipmentTypeStorageTransfer(int $idShipmentType, string $storeName): ?ShipmentTypeStorageTransfer
    {
        $shipmenTypeStorageEntity = $this->getShipmentTypeStorageQuery()
            ->filterByFkShipmentType($idShipmentType)
            ->filterByStore($storeName)
            ->findOne();

        if ($shipmenTypeStorageEntity === null) {
            return null;
        }

        return (new ShipmentTypeStorageTransfer())->fromArray($shipmenTypeStorageEntity->getData(), true);
    }

    /**
     * @return void
     */
    public function ensureShipmentTypeStorageTableIsEmpty(): void
    {
        $this->getShipmentTypeStorageQuery()->deleteAll();
        $this->getShipmentTypeListStorageQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureStoreTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getStoreQuery());
    }

    /**
     * @return int
     */
    public function getShipmentTypeStorageEntitiesCount(): int
    {
        return $this->getShipmentTypeStorageQuery()->count();
    }

    /**
     * @return int
     */
    public function getShipmentTypeListStorageEntitiesCount(): int
    {
        return $this->getShipmentTypeStorageQuery()->count();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    public function createFilterTransfer(int $offset = 0, int $limit = 0): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer
     */
    public function createShipmentTypeStorageTransfer(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeStorageTransfer
    {
        return (new ShipmentTypeStorageTransfer())
            ->fromArray($shipmentTypeTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function createShipmentTypeStorage(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer, StoreTransfer $storeTransfer): void
    {
        $shipmentTypeStorageEntity = new SpyShipmentTypeStorage();
        $shipmentTypeStorageEntity->setFkShipmentType($shipmentTypeStorageTransfer->getIdShipmentTypeOrFail());
        $shipmentTypeStorageEntity->setStore($storeTransfer->getNameOrFail());
        $shipmentTypeStorageEntity->setData($shipmentTypeStorageTransfer->toArray());
        $shipmentTypeStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<string, mixed> $shipmentMethodSeedData
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function haveShipmentMethodWithShipmentTypeRelation(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        StoreTransfer $storeTransfer,
        array $shipmentMethodSeedData = []
    ): ShipmentMethodTransfer {
        $shipmentMethodTransfer = $this->haveShipmentMethod(
            $shipmentMethodSeedData,
            [],
            [],
            [$storeTransfer->getIdStoreOrFail()],
        );

        $this->haveShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        return $shipmentMethodTransfer;
    }

    /**
     * @return void
     */
    public function setUpShipmentTypeShipmentMethodCollectionExpanderPluginDependency(): void
    {
        $this->setDependency(static::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER, function () {
            return [
                new ShipmentTypeShipmentMethodCollectionExpanderPlugin(),
            ];
        });
    }

    /**
     * @return void
     */
    public function setUpQueueAdapter(): void
    {
        $this->setDependency(static::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getShipmentTypeStorageExpanderPluginMock(): ShipmentTypeStorageExpanderPluginInterface
    {
        return Stub::makeEmpty(ShipmentTypeStorageExpanderPluginInterface::class, [
            'expand' => Expected::once(function (array $shipmentTypeStorageTransfers) {
                return $shipmentTypeStorageTransfers;
            }),
        ]);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getShipmentTypeFacadeMock(): ShipmentTypeStorageToShipmentTypeFacadeInterface
    {
        return Stub::makeEmpty(ShipmentTypeStorageToShipmentTypeFacadeInterface::class, [
            'getShipmentTypeCollection' => new ShipmentTypeCollectionTransfer(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getStoreFacadeMock(): ShipmentTypeStorageToStoreFacadeInterface
    {
        return Stub::makeEmpty(ShipmentTypeStorageToStoreFacadeInterface::class, [
            'getStoreCollection' => Expected::never(),
        ]);
    }

    /**
     * @return \Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery
     */
    protected function getShipmentTypeStorageQuery(): SpyShipmentTypeStorageQuery
    {
        return SpyShipmentTypeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeListStorageQuery
     */
    protected function getShipmentTypeListStorageQuery(): SpyShipmentTypeListStorageQuery
    {
        return SpyShipmentTypeListStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function getStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
