<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentMethodFormType;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;

class ShipmentFormDataProvider
{
    protected const ADDRESS_LABEL_PATTERN = '%s %s %s, %s %s, %s %s';
    protected const SHIPMENT_METHODS_OPTIONS_NAMES_PATTERN = '%s - %s';

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        ShipmentGuiToSalesFacadeInterface $salesFacade,
        ShipmentGuiToCustomerFacadeInterface $customerFacade,
        ShipmentGuiToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->salesFacade = $salesFacade;
        $this->customerFacade = $customerFacade;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function getData(OrderTransfer $orderTransfer, ?ShipmentTransfer $shipmentTransfer = null): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();

        if ($shipmentTransfer === null) {
            $shipmentTransfer = new ShipmentTransfer();
        }

        $shipmentTransfer = $this->fillShipmentTransfer($shipmentTransfer, $orderTransfer);

        $shipmentGroupTransfer->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->setItems($orderTransfer->getItems());

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function fillShipmentTransfer(ShipmentTransfer $shipmentTransfer, OrderTransfer $orderTransfer): ShipmentTransfer
    {
        $shipmentTransfer = $this->hydrateShipmentAddressTransfer($orderTransfer, $shipmentTransfer);

        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer === null) {
            return $shipmentTransfer;
        }

        $customerAddressTransfer = $this->customerFacade->findCustomerAddressByAddressData($shipmentAddressTransfer);
        if ($customerAddressTransfer !== null) {
            $shipmentTransfer->setShippingAddress($customerAddressTransfer);
        }

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function hydrateShipmentAddressTransfer(
        OrderTransfer $orderTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        $idSalesShipment = $shipmentTransfer->getIdSalesShipment();

        if ($shipmentAddressTransfer === null && $idSalesShipment !== null) {
            $shipmentAddressTransfer = $this->findOrderItemShippingAddressTransfer($orderTransfer, $idSalesShipment);
        }

        if ($shipmentAddressTransfer !== null) {
            $shipmentAddressTransfer->setFkCustomer($orderTransfer->getFkCustomer());
        }

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $shipmentTransfer
     *
     * @return array
     */
    public function getOptions(OrderTransfer $orderTransfer, ?ShipmentTransfer $shipmentTransfer = null): array
    {
        $options = [
            ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES => $this->getShippingAddressesOptions($orderTransfer),
            ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES => $this->getShippingMethodsOptions(),
            AddressFormType::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
            ItemFormType::OPTION_ORDER_ITEMS_CHOICES => [],
            ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => null,
            ShipmentGroupFormType::FIELD_ID_SALES_SHIPMENT => null,
        ];

        $shipmentSelectedItemsIds = [];
        if ($shipmentTransfer !== null) {
            $shipmentSelectedItemsIds = $this->getShipmentSelectedItemsIds($shipmentTransfer);
        }

        $options[ShipmentGroupFormType::FIELD_SHIPMENT_SELECTED_ITEMS] = $shipmentSelectedItemsIds;
        $options[ItemFormType::OPTION_ORDER_ITEMS_CHOICES] = $orderTransfer->getItems();

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    public function getShipmentSelectedItemsIds(ShipmentTransfer $shipmentTransfer): array
    {
        $salesItems = $this->salesFacade->findSalesOrderItemsIdsBySalesShipmentId($shipmentTransfer->getIdSalesShipment());
        if ($salesItems->count() === 0) {
            return [];
        }

        $itemsIds = [];
        foreach ($salesItems as $item) {
            $idSalesOrderItem = $item->getIdSalesOrderItem();
            if ($idSalesOrderItem === null) {
                continue;
            }

            $itemsIds[] = $item->getIdSalesOrderItem();
        }

        return $itemsIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function getShippingAddressesOptions(OrderTransfer $orderTransfer): array
    {
        $addresses = [null => 'New address'];

        $customerTransfer = $orderTransfer->getCustomer();
        if ($customerTransfer === null) {
            return $addresses;
        }

        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
            if ($idCustomerAddress === null) {
                continue;
            }

            $addresses[$idCustomerAddress] = $this->getAddressLabel($addressTransfer);
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
        return sprintf(
            static::ADDRESS_LABEL_PATTERN,
            $addressTransfer->getSalutation(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity()
        );
    }

    /**
     * @return string[]
     */
    protected function getShippingMethodsOptions(): array
    {
        $shipmentMethodCollection = $this->shipmentFacade->getMethods();
        $shipmentMethodOptionNameCollection = [];
        foreach ($shipmentMethodCollection as $shipmentMethodTransfer) {
            $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethod();
            if ($idShipmentMethod === null) {
                continue;
            }

            $shipmentMethodOptionNameCollection[$idShipmentMethod] = sprintf(
                static::SHIPMENT_METHODS_OPTIONS_NAMES_PATTERN,
                $shipmentMethodTransfer->getCarrierName(),
                $shipmentMethodTransfer->getName()
            );
        }

        return $shipmentMethodOptionNameCollection;
    }

    /**
     * @return string[]
     */
    protected function getSalutationOptions(): array
    {
        $salutation = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);
        if (!is_array($salutation) || empty($salutation)) {
            return [];
        }

        $combinedSalutation = array_combine($salutation, $salutation);
        if ($combinedSalutation === false) {
            return [];
        }

        return $combinedSalutation;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function findOrderItemShippingAddressTransfer(
        OrderTransfer $orderTransfer,
        int $idSalesShipment
    ): ?AddressTransfer {
        if ($orderTransfer->getItems()->count() === 0) {
            return null;
        }

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            if ($idSalesShipment !== $shipmentTransfer->getIdSalesShipment()) {
                continue;
            }

            $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
            if ($shipmentAddressTransfer === null) {
                continue;
            }

            return $shipmentAddressTransfer;
        }

        return null;
    }
}
