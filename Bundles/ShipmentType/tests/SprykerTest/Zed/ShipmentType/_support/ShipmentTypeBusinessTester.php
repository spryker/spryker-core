<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ShipmentTypeBuilder;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentType;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery;

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
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentType\PHPMD)
 *
 * @method \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface getFacade(?string $moduleName = null)
 */
class ShipmentTypeBusinessTester extends Actor
{
    use _generated\ShipmentTypeBusinessTesterActions;

    /**
     * @uses \Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'FkShipmentCarrier';

    /**
     * @return void
     */
    public function ensureShipmentTypeDatabaseIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getShipmentTypeQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function createShipmentTypeTransferWithStoreRelation(StoreTransfer $storeTransfer, array $seedData = []): ShipmentTypeTransfer
    {
        return (new ShipmentTypeBuilder($seedData))->withStoreRelation([
            StoreRelationTransfer::STORES => [
                [StoreTransfer::NAME => $storeTransfer->getNameOrFail()],
            ],
        ])->build();
    }

    /**
     * @param int $idShipmentType
     *
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentType|null
     */
    public function findShipmentTypeEntityByIdShipmentType(int $idShipmentType): ?SpyShipmentType
    {
        return $this->getShipmentTypeQuery()->findOneByIdShipmentType($idShipmentType);
    }

    /**
     * @param int $idShipmentType
     *
     * @return int
     */
    public function getShipmentTypeStoreRelationCountByIdShipmentType(int $idShipmentType): int
    {
        return $this->getShipmentTypeStoreQuery()
            ->filterByFkShipmentType($idShipmentType)
            ->count();
    }

    /**
     * @return int
     */
    public function getShipmentTypeEntitiesCount(): int
    {
        return $this->getShipmentTypeQuery()->count();
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
