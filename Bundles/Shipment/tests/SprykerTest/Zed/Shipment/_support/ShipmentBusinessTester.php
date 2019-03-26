<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment;

use Codeception\Actor;
use DateTime;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Shared\Shipment\ShipmentConstants;
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
     * @var int
     */
    protected $incrementNumber = 0;

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

    /**
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createShipmentTransfer(): ShipmentTransfer
    {
        $addressTransfer = (new AddressTransfer())->setIso2Code('DE');

        return (new ShipmentTransfer())
            ->setShippingAddress($addressTransfer)
            ->setMethod($this->haveShipmentMethod());
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createShipmentExpenseTransfer(): ExpenseTransfer
    {
        return (new ExpenseTransfer())
            ->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE)
            ->setUnitGrossPrice(1)
            ->setQuantity(1);
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderSaverTransfer(): SaveOrderTransfer
    {
        return (new SaveOrderTransfer())
            ->setIdSalesOrder($this->getIdSalesOrderEntity());
    }

    /**
     * @return int
     */
    public function getIdSalesOrderEntity(): int
    {
        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setFkSalesOrderAddressBilling($this->getIdSalesOrderAddress());
        $salesOrderEntity->setFirstName('First');
        $salesOrderEntity->setLastName('Last');
        $salesOrderEntity->setEmail('email@email.tld');
        $salesOrderEntity->setOrderReference('order reference' . $this->getIncrementNumber());
        $salesOrderEntity->save();

        return $salesOrderEntity->getIdSalesOrder();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getIdSalesOrderItemEntity(int $idSalesOrder): int
    {
        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setFkOmsOrderItemState($this->createIdOmsState());
        $salesOrderItemEntity->setFkOmsOrderProcess($this->getIdOmsProcess());
        $salesOrderItemEntity->setFkSalesOrder($idSalesOrder);
        $salesOrderItemEntity->setGrossPrice(1500);
        $salesOrderItemEntity->setQuantity(2);
        $salesOrderItemEntity->setSku('test-item-sku' . $this->getIncrementNumber());
        $salesOrderItemEntity->setName('name-of-order-item' . $this->getIncrementNumber());
        $salesOrderItemEntity->setTaxRate(19);
        $salesOrderItemEntity->setLastStateChange(new DateTime());
        $salesOrderItemEntity->save();

        return $salesOrderItemEntity->getIdSalesOrderItem();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransfer(int $idSalesOrder): ItemTransfer
    {
        return (new ItemTransfer())
            ->setIdSalesOrderItem($this->getIdSalesOrderItemEntity($idSalesOrder));
    }

    /**
     * @return int
     */
    public function createIdOmsState(): int
    {
        $omsStateEntity = new SpyOmsOrderItemState();
        $omsStateEntity->setName('test-' . $this->getIncrementNumber());
        $omsStateEntity->save();

        return $omsStateEntity->getIdOmsOrderItemState();
    }

    /**
     * @return int
     */
    public function getIdOmsProcess(): int
    {
        $omsProcessEntity = new SpyOmsOrderProcess();
        $omsProcessEntity->setName('test-' . $this->getIncrementNumber());
        $omsProcessEntity->save();

        return $omsProcessEntity->getIdOmsOrderProcess();
    }

    /**
     * @return int
     */
    public function getIdSalesOrderAddress(): int
    {
        $salesOrderAddressEntity = (new SpySalesOrderAddress())
            ->setFkCountry(1)
            ->setFirstName('Test' . $this->getIncrementNumber())
            ->setLastName('Spryker')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $salesOrderAddressEntity->save();

        return $salesOrderAddressEntity->getIdSalesOrderAddress();
    }

    /**
     * @return int
     */
    public function getIncrementNumber(): int
    {
        return ++$this->incrementNumber;
    }
}
