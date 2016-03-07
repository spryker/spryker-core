<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class AddressFormDataProvider extends AbstractCustomerDataProvider
{

    const PREFERRED_COUNTRY_NAME = 'Germany';

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface $countryFacade
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(CustomerToCountryInterface $countryFacade, CustomerQueryContainerInterface $customerQueryContainer)
    {
        $this->countryFacade = $countryFacade;
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @param int|null $idCustomerAddress
     *
     * @return array
     */
    public function getData($idCustomerAddress = null)
    {
        if ($idCustomerAddress === null) {
            return [];
        }

        $addressEntity = $this->customerQueryContainer->queryAddress($idCustomerAddress)->findOne();

        return $addressEntity->toArray();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            AddressForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            AddressForm::OPTION_COUNTRY_CHOICES => $this->getCountryChoices(),
            AddressForm::OPTION_PREFERRED_COUNTRY_CHOICES => $this->getPreferredCountryChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getCountryChoices()
    {
        $countryCollection = $this->countryFacade->getAvailableCountries();

        $result = [];
        if (count($countryCollection->getCountries()) > 0) {
            foreach ($countryCollection->getCountries() as $country) {
                $result[$country->getIdCountry()] = $country->getName();
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getPreferredCountryChoices()
    {
        return [
            $this->countryFacade->getPreferredCountryByName(self::PREFERRED_COUNTRY_NAME)->getIdCountry(),
        ];
    }

}
