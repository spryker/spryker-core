<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class EmailValidator implements EmailValidatorInterface
{
    protected const COL_EMAIL_MAX_ALLOWED_LENGHT = 100;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface $utilValidateService
     */
    public function __construct(CustomerQueryContainerInterface $queryContainer, CustomerToUtilValidateServiceInterface $utilValidateService)
    {
        $this->queryContainer = $queryContainer;
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isFormatValid($email)
    {
        return $this->utilValidateService->isEmailFormatValid($email);
    }

    /**
     * @param string $email
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isEmailAvailableForCustomer($email, $idCustomer)
    {
        $customerEntity = $this->queryContainer
            ->queryCustomerByEmailApartFromIdCustomer($email, $idCustomer)
            ->findOne();

        return ($customerEntity === null);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailLengthValid(string $email): bool
    {
        return mb_strlen($email) <= static::COL_EMAIL_MAX_ALLOWED_LENGHT;
    }
}
