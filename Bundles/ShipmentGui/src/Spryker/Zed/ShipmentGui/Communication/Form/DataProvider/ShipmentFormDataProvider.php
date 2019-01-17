<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\ShipmentGui\Communication\Form\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class ShipmentFormDataProvider
{
    /**
     * @var Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface $countryFacade
     */
    public function __construct(
        ShipmentGuiToShipmentInterface $shipmentFacade,
        ShipmentGuiToCountryInterface $countryFacade
    )
    {
        $this->shipmentFacade = $shipmentFacade;
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    public function getData(ShipmentTransfer $shipmentTransfer)
    {
        if ($shipmentTransfer === null) {
            return [];
        }

        $data = [
            ShipmentForm::FIELD_ID_SALES_SHIPMENT => $shipmentTransfer->getIdSalesShipment(),
            ShipmentForm::FIELD_SHIPMENT_ADDRESS_ID => $shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress(),
            ShipmentForm::FIELD_SHIPMENT_METHOD => $shipmentTransfer->getMethod(),
            ShipmentForm::FIELD_SHIPMENT_DATE => $shipmentTransfer->getRequestedDeliveryDate(),
            ShipmentForm::FIELD_ORDER_ITEMS => $shipmentTransfer->getShipmentItems(),
        ];

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return array
     */
    public function getOptions(ShipmentTransfer $shipmentTransfer)
    {
        return [
            ShipmentForm::OPTION_SHIPMENT_METHOD => $this->getAvailableShipmentMethods(),
            ShipmentForm::OPTION_SHIPMENT_ADDRESS => $this->getAvailableAddresses($shipmentTransfer),
            ShipmentForm::SELECTED_ORDER_ITEMS => $this->getSelectedOrderItems(),
            AddressForm::OPTION_COUNTRY_CHOICES => $this->getCountryOptionList(),
            AddressForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptionList(),
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableShipmentMethods()
    {
        $methods = $this->shipmentFacade->getMethods();

        $result = [];

        foreach ($methods as $method) {
            $result[$method->getName()] = $method->getName();
        }

        return $result;
    }

    protected function getAvailableAddresses(ShipmentTransfer $shipmentTransfer)
    {
        $shippingAddress = $shipmentTransfer->getShippingAddress();

        $addresses = [
            $shippingAddress->getIdSalesOrderAddress() => implode(', ', [
                $shippingAddress->getSalutation() . ' ' . $shippingAddress->getFirstName() . ' ' . $shippingAddress->getLastName(),
                $shippingAddress->getAddress1() . ' ' . $shippingAddress->getAddress2(),
                $shippingAddress->getZipCode() . ' ' . $shippingAddress->getCity(),
            ]),
            '0' => sprintf('Create New'),
        ];

        return $addresses;
    }

    /**
     * @return array
     */
    protected function getCountryOptionList()
    {
        $availableCountryCollectionTransfer = $this->countryFacade->getAvailableCountries();

        $countryList = [];
        foreach ($availableCountryCollectionTransfer->getCountries() as $countryTransfer) {
            $countryList[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $countryList;
    }

    /**
     * @return array
     */
    protected function getSalutationOptionList()
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutationSet, $salutationSet);
    }

    protected function getSelectedOrderItems()
    {
        return [];
    }
}