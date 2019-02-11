<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): array
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
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    protected function getShippingAddressesOptions(int $idSalesOrder, ?int $idSalesShipment): array
    {
        $addresses = ['New address'];

        $shipmentTransfer = $this->shipmentFacade->findShipmentById($idSalesShipment);
        if ($shipmentTransfer === null || $shipmentTransfer->getShippingAddress() === null) {
            return $addresses;
        }

        $addressTransfer = $shipmentTransfer->getShippingAddress();
        $addresses[$addressTransfer->getIdSalesOrderAddress()] = $this->getAddressLabel($addressTransfer);

        return $addresses;
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
}
