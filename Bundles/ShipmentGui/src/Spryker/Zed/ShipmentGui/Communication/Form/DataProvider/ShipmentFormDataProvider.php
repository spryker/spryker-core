<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use ArrayObject;
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
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setIdSalesShipment($idSalesShipment);
        $shipmentTransfer = $this->fillShipmentTransfer($shipmentTransfer, $idSalesOrder);

        $shipmentGroupTransfer->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->setItems($this->getItemsDefaultFields($idSalesOrder));

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int|null $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function fillShipmentTransfer(ShipmentTransfer $shipmentTransfer, ?int $idSalesOrder): ShipmentTransfer
    {
        $idSalesShipment = $shipmentTransfer->getIdSalesShipment();
        if ($idSalesShipment !== null) {
            $shipmentTransfer = $this->shipmentFacade->findShipmentById($idSalesShipment);
        }

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer !== null && $shipmentTransfer !== null) {
            $shipmentTransfer = $this->hydrateShipmentAddressTransfer($orderTransfer, $shipmentTransfer);
        }

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
        if ($shipmentAddressTransfer === null) {
            $shipmentAddressTransfer = $orderTransfer->requireShippingAddress()->getShippingAddress();
        }

        if ($shipmentAddressTransfer !== null) {
            $shipmentAddressTransfer->setFkCustomer($orderTransfer->getFkCustomer());
        }

        return $shipmentTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemsDefaultFields(int $idSalesOrder): ArrayObject
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null || $orderTransfer->getItems()->count() === 0) {
            return new ArrayObject();
        }

        return $orderTransfer->getItems();
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $options = [
            ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES => $this->getShippingAddressesOptions($idSalesOrder),
            ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES => $this->getShippingMethodsOptions(),
            ShipmentGroupFormType::FIELD_SHIPMENT_SELECTED_ITEMS => $this->getShipmentSelectedItemsIds($idSalesShipment),
            AddressFormType::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
            ItemFormType::OPTION_ORDER_ITEMS_CHOICES => [],
            ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => null,
            ShipmentGroupFormType::FIELD_ID_SALES_SHIPMENT => null,
        ];

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null) {
            return $options;
        }

        $options[ItemFormType::OPTION_ORDER_ITEMS_CHOICES] = $orderTransfer->getItems();

        return $options;
    }

    /**
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getShipmentSelectedItemsIds(?int $idSalesShipment): array
    {
        if ($idSalesShipment === null) {
            return [];
        }

        $salesItems = $this->salesFacade->findSalesOrderItemsIdsBySalesShipmentId($idSalesShipment);
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
     * @param int $idSalesOrder
     *
     * @return array
     */
    protected function getShippingAddressesOptions(int $idSalesOrder): array
    {
        $addresses = [null => 'New address'];

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null) {
            return $addresses;
        }

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
}
