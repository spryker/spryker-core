<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface;

class AbstractShipmentFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface $salesFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerInterface $customerFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface $shipmentFacade
     */
    public function __construct(
        ShipmentGuiToSalesInterface $salesFacade,
        ShipmentGuiToCustomerInterface $customerFacade,
        ShipmentGuiToShipmentInterface $shipmentFacade
    ) {
        $this->salesFacade = $salesFacade;
        $this->customerFacade = $customerFacade;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $orderTransfer = $this->salesFacade
            ->findOrderByIdSalesOrder($idSalesOrder);

        return [
            ShipmentFormCreate::OPTION_SHIPMENT_ADDRESS_CHOICES => $this->getShippingAddressesOptions($orderTransfer),
            ShipmentFormCreate::OPTION_SHIPMENT_METHOD_CHOICES => $this->getShippingMethodsOptions(),
            AddressForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
            ItemForm::OPTION_ORDER_ITEMS_CHOICES => $this->getOrderItemsOptions($orderTransfer),
        ];
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    protected function getDefaultFormFields(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $data = [
            ShipmentFormCreate::FIELD_ID_SALES_SHIPMENT => $idSalesShipment,
            ShipmentFormCreate::FIELD_ID_SALES_ORDER => $idSalesOrder,
            ShipmentFormCreate::FIELD_ID_SHIPMENT_ADDRESS => null,
            ShipmentFormCreate::FIELD_ID_SHIPMENT_METHOD => null,
            ShipmentFormCreate::FIELD_REQUESTED_DELIVERY_DATE => null,
        ];

        $data = array_merge($data, $this->getAddressDefaultFields());
        $data = array_merge($data, $this->getItemsDefaultFields($idSalesOrder));

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getShippingAddressesOptions(OrderTransfer $orderTransfer): array
    {
        $addresses = ['New address'];

        if ($orderTransfer->getCustomer() === null) {
            return $addresses;
        }
        $customerTransfer = $orderTransfer->getCustomer();
        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $addresses[$addressTransfer->getIdCustomerAddress()] = $this->getAddressLabel($addressTransfer);
        }

        return $addresses;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressLabel(AddressTransfer $addressTransfer): string
    {
        return implode(', ', [
            $addressTransfer->getSalutation() . ' ' . $addressTransfer->getFirstName() . ' ' . $addressTransfer->getLastName(),
            $addressTransfer->getAddress1() . ' ' . $addressTransfer->getAddress2(),
            $addressTransfer->getZipCode() . ' ' . $addressTransfer->getCity(),
        ]);
    }

    /**
     * @return array
     */
    protected function getShippingMethodsOptions(): array
    {
        $shipmentMethods = $this->shipmentFacade->getMethods();

        $methods = [];
        foreach ($shipmentMethods as $method) {
            $methods[$method->getIdShipmentMethod()] = $method->getCarrierName() . ' - ' . $method->getName();
        }

        return $methods;
    }

    /**
     * @return array
     */
    protected function getAddressDefaultFields(): array
    {
        return [
            ShipmentFormCreate::FORM_SHIPPING_ADDRESS => [
                AddressForm::ADDRESS_FIELD_SALUTATION => null,
                AddressForm::ADDRESS_FIELD_FIRST_NAME => null,
                AddressForm::ADDRESS_FIELD_MIDDLE_NAME => null,
                AddressForm::ADDRESS_FIELD_LAST_NAME => null,
                AddressForm::ADDRESS_FIELD_EMAIL => null,
                AddressForm::ADDRESS_FIELD_ISO_2_CODE => null,
                AddressForm::ADDRESS_FIELD_ADDRESS_1 => null,
                AddressForm::ADDRESS_FIELD_ADDRESS_2 => null,
                AddressForm::ADDRESS_FIELD_COMPANY => null,
                AddressForm::ADDRESS_FIELD_CITY => null,
                AddressForm::ADDRESS_FIELD_ZIP_CODE => null,
                AddressForm::ADDRESS_FIELD_PO_BOX => null,
                AddressForm::ADDRESS_FIELD_PHONE => null,
                AddressForm::ADDRESS_FIELD_CELL_PHONE => null,
                AddressForm::ADDRESS_FIELD_DESCRIPTION => null,
                AddressForm::ADDRESS_FIELD_COMMENT => null,
            ],
        ];
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    protected function getItemsDefaultFields(int $idSalesOrder): array
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        return [
            ShipmentFormCreate::FORM_SALES_ORDER_ITEMS => $this->getOrderItemsOptions($orderTransfer),
        ];
    }

    /**
     * @return array
     */
    protected function getSalutationOptions(): array
    {
        $salutation = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutation, $salutation);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsOptions(OrderTransfer $orderTransfer): array
    {
        $items = [];
        foreach ($orderTransfer->getItems() as $item) {
            $items[$item->getIdSalesOrderItem()] = $item;
        }

        return $items;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return array
     */
    protected function getShipmentItemsOptions(int $idSalesShipment): array
    {
        return [];
    }
}
