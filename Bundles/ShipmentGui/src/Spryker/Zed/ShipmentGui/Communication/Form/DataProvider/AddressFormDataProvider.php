<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress ;
use Spryker\Zed\ShipmentGui\Communication\Form\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\CustomerForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface;

class AddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface $countryFacade
     */
    public function __construct(
        ShipmentGuiToCountryInterface $countryFacade
    ) {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idOrderAddress, AddressTransfer $addressTransfer)
    {
        return [
            AddressForm::FIELD_FIRST_NAME => $addressTransfer->getFirstName(),
            AddressForm::FIELD_MIDDLE_NAME => $addressTransfer->getMiddleName(),
            AddressForm::FIELD_LAST_NAME => $addressTransfer->getLastName(),
            AddressForm::FIELD_EMAIL => $addressTransfer->getEmail(),
            AddressForm::FIELD_ADDRESS_1 => $addressTransfer->getAddress1(),
            AddressForm::FIELD_ADDRESS_2 => $addressTransfer->getAddress2(),
            AddressForm::FIELD_COMPANY => $addressTransfer->getCompany(),
            AddressForm::FIELD_CITY => $addressTransfer->getCity(),
            AddressForm::FIELD_ZIP_CODE => $addressTransfer->getZipCode(),
            AddressForm::FIELD_PO_BOX => $addressTransfer->getPoBox(),
            AddressForm::FIELD_PHONE => $addressTransfer->getPhone(),
            AddressForm::FIELD_CELL_PHONE => $addressTransfer->getCellPhone(),
            AddressForm::FIELD_DESCRIPTION => $addressTransfer->getDescription(),
            AddressForm::FIELD_COMMENT => $addressTransfer->getComment(),
            AddressForm::FIELD_SALUTATION => $addressTransfer->getSalutation(),
            AddressForm::FIELD_FK_COUNTRY => $addressTransfer->getFkCountry(),
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
