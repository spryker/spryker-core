<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentMethodFormType;

class ShipmentEditFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider
     */
    protected $shipmentFormDefaultDataProvider;

    /**
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider $shipmentFormDefaultDataProvider
     */
    public function __construct(ShipmentFormDefaultDataProvider $shipmentFormDefaultDataProvider)
    {
        $this->shipmentFormDefaultDataProvider = $shipmentFormDefaultDataProvider;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): ShipmentGroupTransfer
    {
        $formData = array_merge(
            $this->shipmentFormDefaultDataProvider->getDefaultFormFields($idSalesOrder, $idSalesShipment),
            $this->prepareFormData($idSalesShipment)
        );

        return $this->shipmentFormDefaultDataProvider->mapFormDataToShipmentGroupTransfer($formData, new ShipmentGroupTransfer());
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $options = $this->shipmentFormDefaultDataProvider->getOptions($idSalesOrder, $idSalesShipment);
        $options[ShipmentGroupFormType::FIELD_SHIPMENT_SELECTED_ITEMS] = $this->shipmentFormDefaultDataProvider
            ->getShipmentSelectedItemsIds($idSalesShipment);

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    protected function getFormData(ShipmentTransfer $shipmentTransfer): array
    {
        $formData = [
            ShipmentGroupFormType::FORM_SHIPMENT => [],
        ];

        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer !== null) {
            $formData[ShipmentGroupFormType::FORM_SHIPMENT] = $this->getAddressFields($shipmentAddressTransfer);
        }

        $formData[ShipmentGroupFormType::FORM_SHIPMENT] = array_merge(
            $formData[ShipmentGroupFormType::FORM_SHIPMENT],
            $this->getShipmentMethod($shipmentTransfer->getMethod()),
            [ShipmentFormType::FIELD_REQUESTED_DELIVERY_DATE => $shipmentTransfer->getRequestedDeliveryDate()],
            [ShipmentFormType::FIELD_ID_SALES_SHIPMENT => $shipmentTransfer->getIdSalesShipment()]
        );

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return int|null
     */
    protected function getCustomerAddressId(?AddressTransfer $addressTransfer = null): ?int
    {
        if ($addressTransfer === null) {
            return null;
        }

        return $addressTransfer->getIdCustomerAddress();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $shipmentMethodTransfer
     *
     * @return array
     */
    protected function getShipmentMethod(?ShipmentMethodTransfer $shipmentMethodTransfer = null): array
    {
        $idShipmentMethod = $shipmentMethodTransfer === null ? null : $shipmentMethodTransfer->getIdShipmentMethod();

        return [
            ShipmentFormType::FORM_SHIPMENT_METHOD => [
                ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => $idShipmentMethod,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return array
     */
    protected function getAddressFields(AddressTransfer $shippingAddressTransfer): array
    {
        return [
            ShipmentFormType::FIELD_SHIPPING_ADDRESS_FORM => [
                AddressFormType::ADDRESS_FIELD_ID_SALES_ORDER_ADDRESS => $shippingAddressTransfer->getIdSalesOrderAddress(),
                AddressFormType::FIELD_ID_CUSTOMER_ADDRESS => $shippingAddressTransfer->getIdCustomerAddress(),
                AddressFormType::ADDRESS_FIELD_SALUTATION => $shippingAddressTransfer->getSalutation(),
                AddressFormType::ADDRESS_FIELD_FIRST_NAME => $shippingAddressTransfer->getFirstName(),
                AddressFormType::ADDRESS_FIELD_MIDDLE_NAME => $shippingAddressTransfer->getMiddleName(),
                AddressFormType::ADDRESS_FIELD_LAST_NAME => $shippingAddressTransfer->getLastName(),
                AddressFormType::ADDRESS_FIELD_EMAIL => $shippingAddressTransfer->getEmail(),
                AddressFormType::ADDRESS_FIELD_ISO_2_CODE => $shippingAddressTransfer->getIso2Code(),
                AddressFormType::ADDRESS_FIELD_ADDRESS_1 => $shippingAddressTransfer->getAddress1(),
                AddressFormType::ADDRESS_FIELD_ADDRESS_2 => $shippingAddressTransfer->getAddress2(),
                AddressFormType::ADDRESS_FIELD_COMPANY => $shippingAddressTransfer->getCompany(),
                AddressFormType::ADDRESS_FIELD_CITY => $shippingAddressTransfer->getCity(),
                AddressFormType::ADDRESS_FIELD_ZIP_CODE => $shippingAddressTransfer->getZipCode(),
                AddressFormType::ADDRESS_FIELD_PO_BOX => $shippingAddressTransfer->getPoBox(),
                AddressFormType::ADDRESS_FIELD_PHONE => $shippingAddressTransfer->getPhone(),
                AddressFormType::ADDRESS_FIELD_CELL_PHONE => $shippingAddressTransfer->getCellPhone(),
                AddressFormType::ADDRESS_FIELD_DESCRIPTION => $shippingAddressTransfer->getDescription(),
                AddressFormType::ADDRESS_FIELD_COMMENT => $shippingAddressTransfer->getComment(),
            ],
        ];
    }

    /**
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    protected function prepareFormData(?int $idSalesShipment = null): array
    {
        if ($idSalesShipment === null) {
            return [];
        }

        $shipmentTransfer = $this->shipmentFormDefaultDataProvider->findShipmentById($idSalesShipment);
        if ($shipmentTransfer === null) {
            return [];
        }

        return $this->getFormData($shipmentTransfer);
    }
}
