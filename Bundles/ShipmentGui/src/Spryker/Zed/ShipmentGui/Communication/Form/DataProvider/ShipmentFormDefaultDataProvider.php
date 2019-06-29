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

class ShipmentFormDefaultDataProvider
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
     * @return array
     */
    public function getDefaultFormFields(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        $defaultShipmentCreateFormFields = [
            ShipmentGroupFormType::FORM_SHIPMENT => [
                ShipmentFormType::FIELD_ID_SALES_SHIPMENT => $idSalesShipment,
                ShipmentFormType::FIELD_SHIPPING_ADDRESS_FORM => $this->getAddressDefaultFields(),
                ShipmentFormType::FORM_SHIPMENT_METHOD => $this->getMethodDefaultFields(),
            ],
            ShipmentFormType::FIELD_SHIPPING_ADDRESS_FORM => [
                ShipmentGroupFormType::FIELD_ID_CUSTOMER_ADDRESS => $this->getIdCustomerAddress($idSalesOrder, $idSalesShipment),
            ],
        ];

        return array_merge($defaultShipmentCreateFormFields, $this->getItemsDefaultFields($idSalesOrder));
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return int|null
     */
    protected function getIdCustomerAddress(int $idSalesOrder, ?int $idSalesShipment): ?int
    {
        $shipmentTransfer = null;
        if ($idSalesShipment !== null) {
            $shipmentTransfer = $this->findShipmentById($idSalesShipment);
        }

        $addressTransfer = $this->hydrateAddressTransfer(
            $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder),
            $shipmentTransfer
        );

        $customerAddressTransfer = $this->customerFacade->findCustomerAddressByAddressData($addressTransfer);

        if ($customerAddressTransfer === null) {
            return null;
        }

        return $customerAddressTransfer->getIdCustomerAddress();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function hydrateAddressTransfer(
        ?OrderTransfer $orderTransfer,
        ?ShipmentTransfer $shipmentTransfer
    ): AddressTransfer {
        $addressTransfer = new AddressTransfer();
        if ($shipmentTransfer === null || $orderTransfer === null) {
            return $addressTransfer;
        }

        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shipmentAddressTransfer === null) {
            $shipmentAddressTransfer = $orderTransfer->requireShippingAddress()->getShippingAddress();
        }

        if ($shipmentAddressTransfer === null) {
            return $addressTransfer;
        }

        return $addressTransfer
            ->fromArray($shipmentAddressTransfer->modifiedToArray(), true)
            ->setFkCustomer($orderTransfer->getFkCustomer());
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

        $options[ItemFormType::OPTION_ORDER_ITEMS_CHOICES] = $this->getOrderItemsOptions($orderTransfer);

        return $options;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer
    {
        return $this->shipmentFacade->findShipmentById($idSalesShipment);
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
     * @param array $formData
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function mapFormDataToShipmentGroupTransfer(
        array $formData,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer = $shipmentGroupTransfer->fromArray($formData, true);

        if (!isset($formData[ShipmentGroupTransfer::ITEMS])) {
            return $shipmentGroupTransfer;
        }

        $itemList = new ArrayObject();
        foreach ($formData[ShipmentGroupTransfer::ITEMS] as $itemTransfer) {
            $itemList->append($itemTransfer);
        }

        return $shipmentGroupTransfer->setItems($itemList);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array[]
     */
    protected function getItemsDefaultFields(int $idSalesOrder): array
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null) {
            return [];
        }

        return [
            ShipmentGroupFormType::FORM_SALES_ORDER_ITEMS => $this->getOrderItemsOptions($orderTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsOptions(OrderTransfer $orderTransfer): array
    {
        $itemCollection = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();
            if ($idSalesOrderItem === null) {
                continue;
            }

            $itemCollection[$idSalesOrderItem] = $itemTransfer;
        }

        return $itemCollection;
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

    /**
     * @return null[]
     */
    protected function getAddressDefaultFields(): array
    {
        return [
            AddressFormType::ADDRESS_FIELD_SALUTATION => null,
            AddressFormType::ADDRESS_FIELD_FIRST_NAME => null,
            AddressFormType::ADDRESS_FIELD_MIDDLE_NAME => null,
            AddressFormType::ADDRESS_FIELD_LAST_NAME => null,
            AddressFormType::ADDRESS_FIELD_EMAIL => null,
            AddressFormType::ADDRESS_FIELD_ISO_2_CODE => null,
            AddressFormType::ADDRESS_FIELD_ADDRESS_1 => null,
            AddressFormType::ADDRESS_FIELD_ADDRESS_2 => null,
            AddressFormType::ADDRESS_FIELD_COMPANY => null,
            AddressFormType::ADDRESS_FIELD_CITY => null,
            AddressFormType::ADDRESS_FIELD_ZIP_CODE => null,
            AddressFormType::ADDRESS_FIELD_PO_BOX => null,
            AddressFormType::ADDRESS_FIELD_PHONE => null,
            AddressFormType::ADDRESS_FIELD_CELL_PHONE => null,
            AddressFormType::ADDRESS_FIELD_DESCRIPTION => null,
            AddressFormType::ADDRESS_FIELD_COMMENT => null,
        ];
    }

    /**
     * @return null[]
     */
    protected function getMethodDefaultFields(): array
    {
        return [
            ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => null,
        ];
    }
}
