<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ShipmentType\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShipmentTypeBuilder;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentType;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStore;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ShipmentTypeHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'FkShipmentType';

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function haveShipmentType(array $seedData = []): ShipmentTypeTransfer
    {
        if (isset($seedData['key'])) {
            $shipmentTypeEntity = $this->getShipmentTypeQuery()->findOneByKey($seedData['key']);

            if ($shipmentTypeEntity) {
                return (new ShipmentTypeTransfer())->fromArray($shipmentTypeEntity->toArray(), true);
            }
        }

        $shipmentTypeTransfer = (new ShipmentTypeBuilder($seedData))->build();
        $shipmentTypeTransfer = $this->createShipmentType($shipmentTypeTransfer);
        if ($shipmentTypeTransfer->getStoreRelation()) {
            $this->createShipmentTypeStoreRelations($shipmentTypeTransfer);
        }

        $this->getDataCleanupHelper()->addCleanup(function () use ($shipmentTypeTransfer): void {
            $this->deleteShipmentType($shipmentTypeTransfer);
        });

        return $shipmentTypeTransfer;
    }

    /**
     * @param int $idShipmentMethod
     * @param int $idShipmentType
     *
     * @return void
     */
    public function haveShipmentMethodShipmentTypeRelation(int $idShipmentMethod, int $idShipmentType): void
    {
        $this->getShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->update([static::COL_FK_SHIPMENT_TYPE => $idShipmentType]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected function createShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer
    {
        $shipmentTypeEntity = new SpyShipmentType();
        $shipmentTypeEntity->fromArray($shipmentTypeTransfer->toArray());
        $shipmentTypeEntity->save();

        return $shipmentTypeTransfer->fromArray($shipmentTypeEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    protected function createShipmentTypeStoreRelations(ShipmentTypeTransfer $shipmentTypeTransfer): void
    {
        foreach ($shipmentTypeTransfer->getStoreRelation()->getStores() as $storeTransfer) {
            $shipmentTypeStoreEntity = new SpyShipmentTypeStore();
            $shipmentTypeStoreEntity
                ->setFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
                ->setFkStore($storeTransfer->getIdStoreOrFail());

            $shipmentTypeStoreEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    protected function deleteShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): void
    {
        $this->getShipmentTypeStoreQuery()
            ->filterByFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
            ->delete();

        $this->getShipmentTypeQuery()
            ->filterByIdShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery
     */
    protected function getShipmentTypeStoreQuery(): SpyShipmentTypeStoreQuery
    {
        return SpyShipmentTypeStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
