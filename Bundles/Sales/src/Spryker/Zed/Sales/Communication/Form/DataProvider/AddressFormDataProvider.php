<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class AddressFormDataProvider extends AbstractSalesFormDataProvider
{

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     */
    public function __construct(
        SalesQueryContainerInterface $salesQueryContainer,
        SalesToCountryInterface $countryFacade
    ) {
        parent::__construct($salesQueryContainer);
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idOrderAddress)
    {
        $address = $this->salesQueryContainer->querySalesOrderAddressById($idOrderAddress)->findOne();

        return [
            AddressForm::FIELD_FIRST_NAME => $address->getFirstName(),
            AddressForm::FIELD_MIDDLE_NAME => $address->getMiddleName(),
            AddressForm::FIELD_LAST_NAME => $address->getLastName(),
            AddressForm::FIELD_EMAIL => $address->getEmail(),
            AddressForm::FIELD_ADDRESS_1 => $address->getAddress1(),
            AddressForm::FIELD_ADDRESS_2 => $address->getAddress2(),
            AddressForm::FIELD_COMPANY => $address->getCompany(),
            AddressForm::FIELD_CITY => $address->getCity(),
            AddressForm::FIELD_ZIP_CODE => $address->getZipCode(),
            AddressForm::FIELD_PO_BOX => $address->getPoBox(),
            AddressForm::FIELD_PHONE => $address->getPhone(),
            AddressForm::FIELD_CELL_PHONE => $address->getCellPhone(),
            AddressForm::FIELD_DESCRIPTION => $address->getDescription(),
            AddressForm::FIELD_COMMENT => $address->getComment(),
            AddressForm::FIELD_SALUTATION => $address->getSalutation(),
            AddressForm::FIELD_FK_COUNTRY => $address->getFkCountry(),
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

}
