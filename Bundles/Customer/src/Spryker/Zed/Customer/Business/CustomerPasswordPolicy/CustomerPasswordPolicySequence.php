<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicySequence extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer {
        if (!$this->config->getCustomerPasswordSequenceLimit()) {
            return $this->proceed($password, $customerResponseTransfer);
        }
        $counter = 0;
        $prevChar = '';
        foreach (mb_str_split($password) as $char) {
            if ($char === $prevChar) {
                $counter++;
            }
            if ($this->$this->config->getCustomerPasswordSequenceLimit() <= $counter) {
                $this->addError($customerResponseTransfer, self::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE);

                break;
            }
            $prevChar = $char;
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
