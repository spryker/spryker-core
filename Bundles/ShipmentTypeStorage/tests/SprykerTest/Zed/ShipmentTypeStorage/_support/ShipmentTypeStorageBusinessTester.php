<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorage;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;

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
     * @return \Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery
     */
    protected function getShipmentTypeStorageQuery(): SpyShipmentTypeStorageQuery
    {
        return SpyShipmentTypeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function getStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
