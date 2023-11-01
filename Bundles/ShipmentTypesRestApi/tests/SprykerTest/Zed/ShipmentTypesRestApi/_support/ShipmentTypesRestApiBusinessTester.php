<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypesRestApi;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface;

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
 * @method \Spryker\Zed\ShipmentTypesRestApi\Business\ShipmentTypesRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentTypesRestApi\PHPMD)
 */
class ShipmentTypesRestApiBusinessTester extends Actor
{
    use _generated\ShipmentTypesRestApiBusinessTesterActions;

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers
     *
     * @return void
     */
    public function mockGetAvailableMethodsByShipment(array $shipmentMethodTransfers): void
    {
        $shipmentMethodsCollectionTransfer = new ShipmentMethodsCollectionTransfer();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
            $shipmentMethodsCollectionTransfer->addShipmentMethods($shipmentMethodsTransfer);
        }

        $shipmentTypesRestApiBusinessFactory = $this->mockFactoryMethod(
            'getShipmentFacade',
            Stub::makeEmpty(ShipmentTypesRestApiToShipmentFacadeInterface::class, [
                'getAvailableMethodsByShipment' => $shipmentMethodsCollectionTransfer,
            ]),
        );

        $this->getFacade()->setFactory($shipmentTypesRestApiBusinessFactory);
    }

    /**
     * @param int $idShipmentMethod
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createShipmentMethodShipmentTypeRelation(int $idShipmentMethod, int $idShipmentType): void
    {
        $shipmentMethodEntity = $this->getShipmentMethodQuery()
            ->filterByIdShipmentMethod($idShipmentMethod)
            ->findOne();

        $shipmentMethodEntity->setFkShipmentType($idShipmentType);
        $shipmentMethodEntity->save();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
