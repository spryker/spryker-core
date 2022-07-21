<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form\DataProvider;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;

class CustomerFormDataProvider extends AbstractCustomerDataProvider
{
    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface $localeFacade
     */
    public function __construct($customerQueryContainer, $localeFacade)
    {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->localeFacade = $localeFacade;
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
}
