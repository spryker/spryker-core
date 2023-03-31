<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class CustomerFormDataProvider extends AbstractCustomerDataProvider
{
    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected CustomerQueryContainerInterface $customerQueryContainer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected CustomerToLocaleInterface $localeFacade;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface
     */
    protected CustomerToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CustomerQueryContainerInterface $customerQueryContainer,
        CustomerToLocaleInterface $localeFacade,
        CustomerToStoreFacadeInterface $storeFacade
    ) {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            CustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            CustomerForm::OPTION_GENDER_CHOICES => $this->getGenderChoices(),
            CustomerForm::OPTION_LOCALE_CHOICES => $this->getLocaleChoices(),
            CustomerForm::OPTION_STORE_CHOICES => $this->getStoreChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getGenderChoices()
    {
        $genderSet = SpyCustomerTableMap::getValueSet(SpyCustomerTableMap::COL_GENDER);

        return array_combine($genderSet, $genderSet);
    }

    /**
     * @return array<int, string>
     */
    protected function getLocaleChoices(): array
    {
        $result = [];

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $result[$localeTransfer->getIdLocaleOrFail()] = $localeTransfer->getLocaleNameOrFail();
        }

        return $result;
    }

    /**
     * @return array<string, string>
     */
    protected function getStoreChoices(): array
    {
        $storeNames = [];

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $storeNames[$storeTransfer->getNameOrFail()] = $storeTransfer->getNameOrFail();
        }

        return $storeNames;
    }
}
