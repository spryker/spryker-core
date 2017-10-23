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
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct($customerQueryContainer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationChoices(),
            CustomerForm::OPTION_GENDER_CHOICES => $this->getGenderChoices(),
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
}
