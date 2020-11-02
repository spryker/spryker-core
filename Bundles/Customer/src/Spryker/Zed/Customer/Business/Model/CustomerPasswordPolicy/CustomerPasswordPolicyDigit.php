<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyDigit extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ERROR_DIGIT = 'customer.password.error.digit';

    public const PASSWORD_POLICY_CHARSET_DIGIT = '/\p{N}+/';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (empty($this->config)) {
            return $this->nextCustomerPasswordPolicy->validatePassword($password, $customerResponseTransfer);
        }

        $digitRequired = $this->config[static::PASSWORD_POLICY_ATTRIBUTE_REQUIRED] ?? false;
        if ($digitRequired && preg_match(static::PASSWORD_POLICY_CHARSET_DIGIT, $password)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_DIGIT);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
