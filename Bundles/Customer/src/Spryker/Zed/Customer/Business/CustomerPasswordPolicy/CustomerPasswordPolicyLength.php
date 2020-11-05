<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyLength extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';

    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        $passwordLength = mb_strlen($password);
        if ($this->config->getCustomerPasswordMinLength() && $passwordLength < $this->config->getCustomerPasswordMinLength()) {
            $this->addError($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MIN);
        }

        if ($this->config->getCustomerPasswordMaxLength() && $passwordLength > $this->config->getCustomerPasswordMaxLength()) {
            $this->addError($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_MAX);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
