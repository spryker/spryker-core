<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm;

class CustomerUpdateFormDataProvider extends CustomerFormDataProvider
{
    /**
     * @param int|null $idCustomer
     *
     * @return array
     */
    public function getData($idCustomer = null)
    {
        if ($idCustomer === null) {
            return parent::getData();
        }

        $customerEntity = $this
            ->customerQueryContainer
            ->queryCustomerById($idCustomer)
            ->findOne();

        if ($customerEntity === null) {
            return parent::getData();
        }

        $data = $customerEntity->toArray();
        $data[CustomerForm::FIELD_LOCALE] = $customerEntity->getLocale();

        return $data;
    }

    /**
     * @param int|null $idCustomer
     *
     * @return array
     */
    public function getOptions($idCustomer = null)
    {
        $options = parent::getOptions();

        if ($idCustomer !== null) {
            $options[CustomerUpdateForm::OPTION_ADDRESS_CHOICES] = $this->getAddressChoices($idCustomer);
        }

        return $options;
    }

    /**
     * @param int $idCustomer
     *
     * @return array
     */
    protected function getAddressChoices($idCustomer)
    {
        $addresses = $this
            ->customerQueryContainer
            ->queryAddressByIdCustomer($idCustomer)
            ->find();

        $result = [];
        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                $result[$address->getIdCustomerAddress()] = sprintf(
                    '%s %s (%s, %s %s)',
                    $address->getFirstName(),
                    $address->getLastName(),
                    $address->getAddress1(),
                    $address->getZipCode(),
                    $address->getCity()
                );
            }
        }

        return $result;
    }
}
