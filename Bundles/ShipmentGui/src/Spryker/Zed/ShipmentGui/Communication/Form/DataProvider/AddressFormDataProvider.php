<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress ;
use Spryker\Zed\ShipmentGui\Communication\Form\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\CustomerForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface;
use Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface;

class AddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface
     */
    protected $shipmentGuiRepository;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface $shipmentGuiQueryContainer
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface $countryFacade
     */
    public function __construct(
        ShipmentGuiRepositoryInterface $shipmentGuiRepository,
        ShipmentGuiToCountryInterface $countryFacade
    ) {
        $this->shipmentGuiRepository = $shipmentGuiRepository;
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idOrderAddress, SpySalesOrderAddress $shipmentAddressEntity)
    {
        return [
            AddressForm::FIELD_FIRST_NAME => $shipmentAddressEntity->getFirstName(),
            AddressForm::FIELD_MIDDLE_NAME => $shipmentAddressEntity->getMiddleName(),
            AddressForm::FIELD_LAST_NAME => $shipmentAddressEntity->getLastName(),
            AddressForm::FIELD_EMAIL => $shipmentAddressEntity->getEmail(),
            AddressForm::FIELD_ADDRESS_1 => $shipmentAddressEntity->getAddress1(),
            AddressForm::FIELD_ADDRESS_2 => $shipmentAddressEntity->getAddress2(),
            AddressForm::FIELD_COMPANY => $shipmentAddressEntity->getCompany(),
            AddressForm::FIELD_CITY => $shipmentAddressEntity->getCity(),
            AddressForm::FIELD_ZIP_CODE => $shipmentAddressEntity->getZipCode(),
            AddressForm::FIELD_PO_BOX => $shipmentAddressEntity->getPoBox(),
            AddressForm::FIELD_PHONE => $shipmentAddressEntity->getPhone(),
            AddressForm::FIELD_CELL_PHONE => $shipmentAddressEntity->getCellPhone(),
            AddressForm::FIELD_DESCRIPTION => $shipmentAddressEntity->getDescription(),
            AddressForm::FIELD_COMMENT => $shipmentAddressEntity->getComment(),
            AddressForm::FIELD_SALUTATION => $shipmentAddressEntity->getSalutation(),
            AddressForm::FIELD_FK_COUNTRY => $shipmentAddressEntity->getFkCountry(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
            AddressForm::OPTION_COUNTRY_CHOICES => $this->createCountryOptionList(),
        ];
    }

    /**
     * @return array
     */
    protected function createCountryOptionList()
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
    protected function getSalutationOptions()
    {
        $salutationSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_SALUTATION);

        return array_combine($salutationSet, $salutationSet);
    }
}
