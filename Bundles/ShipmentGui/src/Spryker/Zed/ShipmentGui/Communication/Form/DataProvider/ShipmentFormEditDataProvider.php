<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormEdit;

class ShipmentFormEditDataProvider implements ShipmentFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface
     */
    protected $shipmentFormDefaultDataProvider;

    /**
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface $shipmentFormDefaultDataProvider
     */
    public function __construct(ShipmentFormDefaultDataProviderInterface $shipmentFormDefaultDataProvider)
    {
        $this->shipmentFormDefaultDataProvider = $shipmentFormDefaultDataProvider;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array[]
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        return array_merge(
            $this->shipmentFormDefaultDataProvider->getDefaultFormFields($idSalesOrder, $idSalesShipment),
            $this->prepareFormData($idSalesShipment)
        );
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array[]
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $options = $this->shipmentFormDefaultDataProvider->getOptions($idSalesOrder, $idSalesShipment);
        $options[ShipmentFormEdit::FIELD_SHIPMENT_SELECTED_ITEMS] = $this->shipmentFormDefaultDataProvider
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
            ShipmentFormCreate::FIELD_ID_CUSTOMER_ADDRESS => $this->getCustomerAddressId($shipmentTransfer->getShippingAddress()),
            ShipmentFormCreate::FIELD_ID_SHIPMENT_METHOD => $this->getShipmentMethodId($shipmentTransfer->getMethod()),
            ShipmentFormCreate::FIELD_REQUESTED_DELIVERY_DATE => $shipmentTransfer->getRequestedDeliveryDate(),
            ShipmentFormCreate::FORM_SHIPPING_ADDRESS => [],
        ];

        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer === null) {
            return $formData;
        }

        $formData[ShipmentFormCreate::FORM_SHIPPING_ADDRESS] = $this->getAddressFields($shipmentAddressTransfer);

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
     * @return int|null
     */
    protected function getShipmentMethodId(?ShipmentMethodTransfer $shipmentMethodTransfer = null): ?int
    {
        if ($shipmentMethodTransfer === null) {
            return null;
        }

        return $shipmentMethodTransfer->getIdShipmentMethod();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return array
     */
    protected function getAddressFields(AddressTransfer $shippingAddressTransfer): array
    {
        return [
            AddressForm::ADDRESS_FIELD_ID_SALES_ORDER_ADDRESS => $shippingAddressTransfer->getIdSalesOrderAddress(),
            AddressForm::ADDRESS_FIELD_SALUTATION => $shippingAddressTransfer->getSalutation(),
            AddressForm::ADDRESS_FIELD_FIRST_NAME => $shippingAddressTransfer->getFirstName(),
            AddressForm::ADDRESS_FIELD_MIDDLE_NAME => $shippingAddressTransfer->getMiddleName(),
            AddressForm::ADDRESS_FIELD_LAST_NAME => $shippingAddressTransfer->getLastName(),
            AddressForm::ADDRESS_FIELD_EMAIL => $shippingAddressTransfer->getEmail(),
            AddressForm::ADDRESS_FIELD_ISO_2_CODE => $shippingAddressTransfer->getIso2Code(),
            AddressForm::ADDRESS_FIELD_ADDRESS_1 => $shippingAddressTransfer->getAddress1(),
            AddressForm::ADDRESS_FIELD_ADDRESS_2 => $shippingAddressTransfer->getAddress2(),
            AddressForm::ADDRESS_FIELD_COMPANY => $shippingAddressTransfer->getCompany(),
            AddressForm::ADDRESS_FIELD_CITY => $shippingAddressTransfer->getCity(),
            AddressForm::ADDRESS_FIELD_ZIP_CODE => $shippingAddressTransfer->getZipCode(),
            AddressForm::ADDRESS_FIELD_PO_BOX => $shippingAddressTransfer->getPoBox(),
            AddressForm::ADDRESS_FIELD_PHONE => $shippingAddressTransfer->getPhone(),
            AddressForm::ADDRESS_FIELD_CELL_PHONE => $shippingAddressTransfer->getCellPhone(),
            AddressForm::ADDRESS_FIELD_DESCRIPTION => $shippingAddressTransfer->getDescription(),
            AddressForm::ADDRESS_FIELD_COMMENT => $shippingAddressTransfer->getComment(),
        ];
    }

    /**
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function prepareFormData(?int $idSalesShipment = null): array
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
