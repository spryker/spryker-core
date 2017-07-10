<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class AddressFormDataProvider extends AbstractCustomerDataProvider
{

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface $countryFacade
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(CustomerToCountryInterface $countryFacade, CustomerQueryContainerInterface $customerQueryContainer, Store $store)
    {
        $this->countryFacade = $countryFacade;
        $this->customerQueryContainer = $customerQueryContainer;
        $this->store = $store;
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
        ];
    }

    /**
     * @return array
     */
    protected function getCountryChoices()
    {
        $result = [];
        foreach ($this->store->getCountries() as $iso2Code) {
            $countryTransfer = $this->countryFacade->getCountryByIso2Code($iso2Code);
            $result[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $result;
    }

}
