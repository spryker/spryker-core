<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class ForbiddenCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    protected const PASSWORD_POLICY_ERROR_FORBIDDEN = 'customer.password.error.forbidden';

    /**
     * @var string
     */
    protected $forbiddenCharactersList;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->forbiddenCharactersList = $customerConfig->getCustomerPasswordForbiddenCharacters();
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (empty($this->forbiddenCharactersList)) {
            return $customerResponseTransfer;
        }
        $forbiddenCharacters = mb_str_split($this->forbiddenCharactersList);
        if (!empty(array_intersect(mb_str_split($password), $forbiddenCharacters))) {
            $customerErrorTransfer = (new CustomerErrorTransfer())
                ->setMessage(static::PASSWORD_POLICY_ERROR_FORBIDDEN);
            $customerResponseTransfer->setIsSuccess(false)
                ->addError($customerErrorTransfer);

            return $customerResponseTransfer;
        }

        return $customerResponseTransfer;
    }
}
