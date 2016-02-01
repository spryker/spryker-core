<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;

class AddressFormDataProvider extends AbstractSalesFormDataProvider
{

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
            AddressForm::FIELD_COMPANY => $address->getCompany(),
            AddressForm::FIELD_CITY => $address->getCity(),
            AddressForm::FIELD_ZIP_CODE => $address->getZipCode(),
            AddressForm::FIELD_PO_BOX => $address->getPoBox(),
            AddressForm::FIELD_PHONE => $address->getPhone(),
            AddressForm::FIELD_CELL_PHONE => $address->getCellPhone(),
            AddressForm::FIELD_DESCRIPTION => $address->getDescription(),
            AddressForm::FIELD_COMMENT => $address->getComment(),
            AddressForm::FIELD_SALUTATION => $address->getSalutation(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
        ];
    }

}
