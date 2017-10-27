<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class EmailValidator implements EmailValidatorInterface
{
    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateInterface $utilValidateService
     */
    public function __construct(CustomerQueryContainerInterface $queryContainer, CustomerToUtilValidateInterface $utilValidateService)
    {
        $this->queryContainer = $queryContainer;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return bool
     */
    public function isFormatValid(SpyCustomer $customerEntity)
    {
        return $this->utilValidateService->isEmailFormatValid($customerEntity->getEmail());
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return bool
     */
    public function isEmailAvailableForCustomer(SpyCustomer $customerEntity)
    {
        $count = $this->queryContainer
            ->queryCustomerByEmailApartFromIdCustomer($customerEntity->getEmail(), $customerEntity->getIdCustomer())
            ->count();

        return ($count === 0);
    }
}
