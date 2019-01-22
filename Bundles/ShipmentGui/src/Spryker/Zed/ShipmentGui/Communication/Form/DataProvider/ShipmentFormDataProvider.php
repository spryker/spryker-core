<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ShipmentGui\Communication\Form\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class ShipmentFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface $salesFacade
     */
    public function __construct(
        ShipmentGuiToShipmentInterface $shipmentFacade,
        ShipmentGuiToSalesInterface $salesFacade
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getData(ShipmentTransfer $shipmentTransfer, OrderTransfer $orderTransfer): array
    {
        return [
            ShipmentForm::FIELD_ID_SALES_SHIPMENT => $shipmentTransfer->getIdSalesShipment(),
            ShipmentForm::FIELD_SHIPMENT_ADDRESS_ID => $shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress(),
            ShipmentForm::FIELD_ADDRESS => $shipmentTransfer->getShippingAddress(),
            ShipmentForm::FIELD_ORDER_ITEMS => $this->getSalesOrderItems($orderTransfer),
            ShipmentForm::FIELD_SHIPMENT_METHOD => $shipmentTransfer->getMethodName(),
            ShipmentForm::FIELD_SHIPMENT_DATE => $shipmentTransfer->getRequestedDeliveryDate(),

        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    public function getOptions(ShipmentTransfer $shipmentTransfer): array
    {
        return [
            ShipmentForm::OPTION_SHIPMENT_METHOD => $this->getAvailableShipmentMethods(),
            ShipmentForm::OPTION_SHIPMENT_ADDRESS => $this->getAvailableAddresses($shipmentTransfer->getShippingAddress()),
            ShipmentForm::OPTION_SELECTED_ORDER_ITEMS => $this->getSelectedOrderItems($shipmentTransfer->getIdSalesShipment()),
            AddressForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableShipmentMethods(): array
    {
        $methods = $this->shipmentFacade->getMethods();

        $result = [];
        foreach ($methods as $method) {
            $result[$method->getName()] = $method->getCarrierName() . ' - ' . $method->getName();
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return array
     */
    protected function getAvailableAddresses(AddressTransfer $addressTransfer): array
    {
        return [
            $addressTransfer->getIdSalesOrderAddress() => implode(', ', [
                $addressTransfer->getSalutation() . ' ' . $addressTransfer->getFirstName() . ' ' . $addressTransfer->getLastName(),
                $addressTransfer->getAddress1() . ' ' . $addressTransfer->getAddress2(),
                $addressTransfer->getZipCode() . ' ' . $addressTransfer->getCity(),
            ]),
            '0' => sprintf('Create New'),
        ];
    }

    /**
     * @param int $idSalesShipment
     *
     * @return array
     */
    protected function getSelectedOrderItems(int $idSalesShipment): array
    {
        $shipmentItems = $this->shipmentFacade->findShipmentItemsByIsSalesShipment($idSalesShipment);

        $result = [];
        foreach ($shipmentItems as $item) {
            $result[] = $item->getIdSalesOrderItem();
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getSalesOrderItems(OrderTransfer $orderTransfer): array
    {
        $orderItems = [];
        foreach ($orderTransfer->getItems() as $item) {
            $orderItems[$item->getIdSalesOrderItem()] = $item;
        }

        return $orderItems;
    }

    /**
     * @return array
     */
    protected function getSalutationOptions(): array
    {
        $salutation = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutation, $salutation);
    }
}
