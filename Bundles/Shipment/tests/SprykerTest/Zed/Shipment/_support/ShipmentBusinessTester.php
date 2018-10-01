<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment;

use Codeception\Actor;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentBusinessTester extends Actor
{
    use _generated\ShipmentBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    public function getShipmentFacade()
    {
        return $this->getLocator()->shipment()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return int[]
     */
    public function getIdShipmentMethodCollection(ShipmentMethodsTransfer $shipmentMethodsTransfer)
    {
        $idShipmentMethodCollection = array_column($shipmentMethodsTransfer->toArray(true)['methods'], 'id_shipment_method');
        sort($idShipmentMethodCollection);

        return $idShipmentMethodCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|mixed|null
     */
    public function findShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer, $idShipmentMethod)
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param array|null $idFilter
     *
     * @return void
     */
    public function updateShipmentMethod(array $data, ?array $idFilter = null)
    {
        $shipmentMethodQuery = SpyShipmentMethodQuery::create();

        if ($idFilter !== null) {
            $shipmentMethodQuery->filterByIdShipmentMethod($idFilter, Criteria::IN);
        }

        $shipmentMethodCollection = $shipmentMethodQuery->find();
        foreach ($shipmentMethodCollection as $shipmentMethodEntity) {
            $shipmentMethodEntity->fromArray($data);
            $shipmentMethodEntity->save();
        }
    }

    /**
     * @return void
     */
    public function disableAllShipmentMethods()
    {
        $this->updateShipmentMethod(['is_active' => false]);
    }

    /**
     * @param int $shipmentMethodCount
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function haveActiveShipmentMethods($shipmentMethodCount)
    {
        $shipmentMethodTransferCollection = [];
        for ($i = 0; $i < $shipmentMethodCount; $i++) {
            $shipmentMethodTransferCollection[$i] = $this->haveShipmentMethod(['is_active' => true]);
        }

        return $shipmentMethodTransferCollection;
    }

    /**
     * @return string
     */
    public function getDefaultStoreName()
    {
        return $this->getLocator()->store()->facade()->getCurrentStore()->getName();
    }
}
