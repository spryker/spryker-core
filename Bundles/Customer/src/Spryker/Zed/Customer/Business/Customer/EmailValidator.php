<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

class EmailValidator implements EmailValidatorInterface
{
    /**
     * @var int
     */
    protected const COL_EMAIL_MAX_ALLOWED_LENGTH = 100;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface $utilValidateService
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerToUtilValidateServiceInterface $utilValidateService,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->utilValidateService = $utilValidateService;
        $this->customerRepository = $customerRepository;
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
        return $this->customerRepository->isEmailAvailableForCustomer($email, $idCustomer);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailLengthValid(string $email): bool
    {
        return mb_strlen($email) <= static::COL_EMAIL_MAX_ALLOWED_LENGTH;
    }
}
