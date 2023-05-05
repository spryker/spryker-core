<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeDataImport;

use Codeception\Actor;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentType;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentTypeDataImportCommunicationTester extends Actor
{
    use _generated\ShipmentTypeDataImportCommunicationTesterActions;

    /**
     * @return int
     */
    public function getShipmentTypeEntityCount(): int
    {
        return $this->getShipmentTypeQuery()->count();
    }

    /**
     * @return int
     */
    public function getShipmentTypeStoreEntityCount(): int
    {
        return $this->getShipmentTypeStoreQuery()->count();
    }

    /**
     * @return int
     */
    public function getShipmentMethodWithShipmentTypeEntityCount(): int
    {
        return $this->getShipmentMethodQuery()
            ->filterByFkShipmentType(null, Criteria::ISNOTNULL)
            ->count();
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentType
     */
    public function getShipmentTypeEntity(string $shipmentTypeKey): SpyShipmentType
    {
        return $this->getShipmentTypeQuery()->findOneByKey($shipmentTypeKey);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    public function getShipmentMethodEntity(int $idShipmentMethod): SpyShipmentMethod
    {
        return $this->getShipmentMethodQuery()->findOneByIdShipmentMethod($idShipmentMethod);
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    public function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery
     */
    public function getShipmentTypeStoreQuery(): SpyShipmentTypeStoreQuery
    {
        return SpyShipmentTypeStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
