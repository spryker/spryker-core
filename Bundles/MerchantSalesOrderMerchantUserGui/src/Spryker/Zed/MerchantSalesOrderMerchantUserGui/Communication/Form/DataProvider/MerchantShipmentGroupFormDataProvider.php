<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface;

class MerchantShipmentGroupFormDataProvider
{
    protected const ADDRESS_CHOICE_NEW_ADDRESS_LABEL = 'New address';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::OPTION_SHIPMENT_METHOD_CHOICES
     */
    protected const OPTION_SHIPMENT_METHOD_CHOICES = 'method_choices';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::OPTION_SALUTATION_CHOICES
     */
    protected const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::OPTION_SHIPMENT_ADDRESS_CHOICES
     */
    protected const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::OPTION_ORDER_ITEMS_CHOICES
     */
    protected const OPTION_ORDER_ITEMS_CHOICES = 'items_choices';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::FIELD_ID_SHIPMENT_METHOD
     */
    protected const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::FIELD_ID_SALES_SHIPMENT
     */
    protected const FIELD_ID_SALES_SHIPMENT = 'idSalesShipment';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::FIELD_SHIPMENT_SELECTED_ITEMS
     */
    protected const FIELD_SHIPMENT_SELECTED_ITEMS = 'selected_items';

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface $customerFacade,
        MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->customerFacade = $customerFacade;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function getData(MerchantOrderTransfer $merchantOrderTransfer, ShipmentTransfer $shipmentTransfer): ShipmentGroupTransfer
    {
        $shipmentTransfer = $this->hydrateShipmentTransfer($shipmentTransfer, $merchantOrderTransfer);

        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->setItems($merchantOrderTransfer->getOrder()->getItems());

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function hydrateShipmentTransfer(ShipmentTransfer $shipmentTransfer, MerchantOrderTransfer $merchantOrderTransfer): ShipmentTransfer
    {
        $shipmentTransfer = $this->hydrateShipmentAddressTransfer($merchantOrderTransfer, $shipmentTransfer);

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
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function hydrateShipmentAddressTransfer(
        MerchantOrderTransfer $merchantOrderTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        $shipmentAddressTransfer = $shipmentTransfer->getShippingAddress();
        $idSalesShipment = $shipmentTransfer->getIdSalesShipment();

        if ($shipmentAddressTransfer === null && $idSalesShipment !== null) {
            $shipmentAddressTransfer = $this->findOrderItemShippingAddressTransfer($merchantOrderTransfer, $idSalesShipment);
        }

        if ($shipmentAddressTransfer !== null) {
            $shipmentAddressTransfer->setFkCustomer($merchantOrderTransfer->getOrder()->getFkCustomer());
        }

        return $shipmentTransfer;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $shipmentTransfer
     *
     * @return array
     */
    public function getOptions(MerchantOrderTransfer $merchantOrderTransfer, ?ShipmentTransfer $shipmentTransfer = null): array
    {
        $options = [
            static::OPTION_SHIPMENT_ADDRESS_CHOICES => $this->getShippingAddressesOptions($merchantOrderTransfer),
            static::OPTION_SHIPMENT_METHOD_CHOICES => $this->getShippingMethodsOptions(),
            static::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
            static::OPTION_ORDER_ITEMS_CHOICES => [],
            static::FIELD_ID_SHIPMENT_METHOD => null,
            static::FIELD_ID_SALES_SHIPMENT => null,
        ];

        $shipmentSelectedItemsIds = [];
        if ($shipmentTransfer) {
            $shipmentSelectedItemsIds = $this->getShipmentSelectedItemsIds($merchantOrderTransfer, $shipmentTransfer);
        }

        $options[static::FIELD_SHIPMENT_SELECTED_ITEMS] = $shipmentSelectedItemsIds;

        if ($merchantOrderTransfer->getOrder()) {
            $options[static::OPTION_ORDER_ITEMS_CHOICES] = $merchantOrderTransfer->getOrder()->getItems();
        }

        return $options;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    protected function getShipmentSelectedItemsIds(MerchantOrderTransfer $merchantOrderTransfer, ShipmentTransfer $shipmentTransfer): array
    {
        $salesOrderItemTransfers = $merchantOrderTransfer->getOrder()->getItems();

        $itemsIds = [];
        foreach ($salesOrderItemTransfers as $salesOrderItemTransfer) {
            $idSalesOrderItem = $salesOrderItemTransfer->getIdSalesOrderItem();
            if (!$idSalesOrderItem) {
                continue;
            }

            if ($shipmentTransfer->getIdSalesShipment() !== $salesOrderItemTransfer->getShipment()->getIdSalesShipment()) {
                continue;
            }

            $itemsIds[] = $salesOrderItemTransfer->getIdSalesOrderItem();
        }

        return $itemsIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return string[]
     */
    protected function getShippingAddressesOptions(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $newAddressChoice = [static::ADDRESS_CHOICE_NEW_ADDRESS_LABEL => ''];

        $customerTransfer = $merchantOrderTransfer->getOrder()->getCustomer();
        if ($customerTransfer === null) {
            return $newAddressChoice;
        }

        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);

        if (count($addressesTransfer->getAddresses()) === 0) {
            return $newAddressChoice;
        }

        $addresses = $this->getCustomerAddressChoices($addressesTransfer->getAddresses());
        $addresses = $this->sanitizeDuplicatedCustomerAddressChoices($addresses);

        return $newAddressChoice + $addresses;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressLabel(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            '%s %s %s, %s %s, %s %s',
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
     * @param iterable|\ArrayObject|\Generated\Shared\Transfer\AddressTransfer[] $customerAddressesCollection
     *
     * @return string[]
     */
    protected function getCustomerAddressChoices(iterable $customerAddressesCollection): array
    {
        $choices = [];

        foreach ($customerAddressesCollection as $addressTransfer) {
            $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
            if ($idCustomerAddress === null) {
                continue;
            }

            $choices[$idCustomerAddress] = $this->getAddressLabel($addressTransfer);
        }

        return $choices;
    }

    /**
     * @param iterable|string[] $choices
     *
     * @return string[]
     */
    protected function sanitizeDuplicatedCustomerAddressChoices(iterable $choices): array
    {
        $sanitizedChoices = [];
        $choicesCounts = [];

        foreach ($choices as $idAddress => $addressLabel) {
            if (isset($sanitizedChoices[$addressLabel])) {
                $originAddressLabel = $addressLabel;

                if (isset($choicesCounts[$originAddressLabel])) {
                    continue;
                }

                $choicesCounts[$originAddressLabel] = 1;

                $addressLabel = $this->getSanitizedCustomerAddressChoices($addressLabel, $choicesCounts[$originAddressLabel]);
                $choicesCounts[$originAddressLabel]++;
            }

            $sanitizedChoices[$addressLabel] = $idAddress;
        }

        ksort($sanitizedChoices, SORT_NATURAL);

        return $sanitizedChoices;
    }

    /**
     * @param string $addressLabel
     * @param int $itemNumber
     *
     * @return string
     */
    protected function getSanitizedCustomerAddressChoices(string $addressLabel, int $itemNumber): string
    {
        return sprintf('%s - %s', $addressLabel, $itemNumber);
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
                '%s - %s',
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
        $salutation = $this->customerFacade->getAllSalutations();
        if (!is_array($salutation) || !$salutation) {
            return [];
        }

        $combinedSalutation = array_combine($salutation, $salutation);
        if ($combinedSalutation === false) {
            return [];
        }

        return $combinedSalutation;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function findOrderItemShippingAddressTransfer(
        MerchantOrderTransfer $merchantOrderTransfer,
        int $idSalesShipment
    ): ?AddressTransfer {
        if ($merchantOrderTransfer->getOrder()->getItems()->count() === 0) {
            return null;
        }

        foreach ($merchantOrderTransfer->getOrder()->getItems() as $itemTransfer) {
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
