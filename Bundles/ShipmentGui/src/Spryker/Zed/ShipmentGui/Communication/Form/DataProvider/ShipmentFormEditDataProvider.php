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

class ShipmentFormEditDataProvider extends AbstractShipmentFormDataProvider
{
    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getData(int $idSalesOrder, int $idSalesShipment = null): array
    {
        $shipmentTransfer = $this->shipmentFacade->findShipmentById($idSalesShipment);
        $formData = $shipmentTransfer !== null ? $this->getFormData($shipmentTransfer) : [];
        $defaults = $this->getDefaultFormFields($idSalesOrder, $idSalesShipment);

        $formData = array_merge($defaults, $formData);

        return $formData;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    protected function getFormData(ShipmentTransfer $shipmentTransfer): array
    {
        $data = [
            ShipmentFormCreate::FIELD_ID_SHIPMENT_ADDRESS => $this->getShipmentAddressId($shipmentTransfer->getShippingAddress()),
            ShipmentFormCreate::FIELD_ID_SHIPMENT_METHOD => $this->getShipmentMethodId($shipmentTransfer->getMethod()),
            ShipmentFormCreate::FIELD_REQUESTED_DELIVERY_DATE => $shipmentTransfer->getRequestedDeliveryDate(),
            ShipmentFormCreate::FORM_SHIPPING_ADDRESS => $this->getAddressFields($shipmentTransfer->getShippingAddress()),
        ];

        return $data;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $options = parent::getOptions($idSalesOrder, $idSalesShipment);
        $options[ShipmentFormEdit::FIELD_SHIPMENT_SELECTED_ITEMS] = $this->getShipmentSelectedItemsIds($idSalesShipment);

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return int|null
     */
    protected function getShipmentAddressId(?AddressTransfer $addressTransfer = null): ?int
    {
        if ($addressTransfer === null) {
            return null;
        }

        return $addressTransfer->getIdSalesOrderAddress();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $shipmentMethodTransferTransfer
     *
     * @return int|null
     */
    protected function getShipmentMethodId(?ShipmentMethodTransfer $shipmentMethodTransferTransfer = null): ?int
    {
        if ($shipmentMethodTransferTransfer === null) {
            return null;
        }

        return $shipmentMethodTransferTransfer->getIdShipmentMethod();
    }

    /**
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    protected function getShipmentSelectedItemsIds(?int $idSalesShipment): array
    {
        $salesItems = $this->salesFacade->findSalesOrderItemsIdsBySalesShipmentId($idSalesShipment);

        $itemsIds = [];
        foreach ($salesItems as $item) {
            $itemsIds[] = $item->getIdSalesOrderItem();
        }

        return $itemsIds;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shippingAddressTransfer
     *
     * @return array
     */
    protected function getAddressFields(AddressTransfer $shippingAddressTransfer): array
    {
        return [
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
}
