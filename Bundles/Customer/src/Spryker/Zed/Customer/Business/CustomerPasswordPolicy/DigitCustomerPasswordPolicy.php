<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class DigitCustomerPasswordPolicy extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DIGIT = 'customer.password.error.digit';

    public const PASSWORD_POLICY_CHARSET_DIGIT = '/\p{N}+/';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if ($this->config->getCustomerPasswordDigitRequired() && !preg_match(static::PASSWORD_POLICY_CHARSET_DIGIT, $password)) {
            $this->addError($customerResponseTransfer, static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DIGIT);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
