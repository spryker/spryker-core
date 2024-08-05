<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;
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
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface $countryFacade
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CustomerToCountryInterface $countryFacade,
        CustomerQueryContainerInterface $customerQueryContainer,
        CustomerToStoreFacadeInterface $storeFacade
    ) {
        $this->countryFacade = $countryFacade;
        $this->customerQueryContainer = $customerQueryContainer;
        $this->storeFacade = $storeFacade;
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
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            AddressForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            AddressForm::OPTION_COUNTRY_CHOICES => $this->getCountryChoices(),
        ];
    }

    /**
     * @return array<int|string, string|null>
     */
    protected function getCountryChoices(): array
    {
        $result = [];

        foreach ($this->getContries() as $countryTransfer) {
            $result[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $result;
    }

    /**
     * @return array<\Generated\Shared\Transfer\CountryTransfer>
     */
    protected function getContries(): array
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if ($this->storeFacade->isDynamicStoreEnabled()) {
            return $this->countryFacade->getAvailableCountries()
                ->getCountries()
                ->getIterator()
                ->getArrayCopy();
        }

        $countryTransfers = [];

        foreach ($this->storeFacade->getCurrentStore()->getCountries() as $iso2Code) {
            $countryTransfers[] = $this->countryFacade->getCountryByIso2Code($iso2Code);
        }

        return $countryTransfers;
    }
}
