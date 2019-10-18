<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-10-18
 * Time: 17:58
 */

namespace SprykerTest\Zed\Shipment\Business;


use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStoreQuery;

class UpdateMethodTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateMethodShouldUpdateShipmentMethodWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::NAME => 'test',
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_ENTITY => $shipmentMethodTransfer->getIdShipmentMethod(),
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $shipmentMethodTransfer->setStoreRelation($storeRelationTransfer);
        $shipmentMethodTransfer->setName('test1');

        // Act
        $this->tester->getFacade()->updateMethod($shipmentMethodTransfer);

        // Assert
        $resultShipmentMethodEntity = SpyShipmentMethodQuery::create()
            ->filterByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
            ->findOne();

        $storeRelationExist = SpyShipmentMethodStoreQuery::create()
            ->filterByFkShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
            ->exists();

        $this->assertEquals('test1', $resultShipmentMethodEntity->getName(), 'Shipment method name should match to the expected value');
        $this->assertTrue($storeRelationExist, 'Shipment method store relation should exists');
    }
}
