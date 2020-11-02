<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyLength extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ATTRIBUTE_MIN = 'min';

    public const PASSWORD_POLICY_ATTRIBUTE_MAX = 'max';

    public const PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';

    public const PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

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

        $passwordLength = mb_strlen($password);
        if ($this->getPasswordPolicyConfigMinLimit() && $passwordLength < $this->getPasswordPolicyConfigMinLimit()) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MIN);
        }

        if ($this->getPasswordPolicyConfigMaxLimit() && $passwordLength > $this->getPasswordPolicyConfigMaxLimit()) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MAX);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }

    /**
     * @return int|null
     */
    protected function getPasswordPolicyConfigMaxLimit(): ?int
    {
        return $this->config[static::PASSWORD_POLICY_ATTRIBUTE_MAX] ?? null;
    }

    /**
     * @return int|null
     */
    protected function getPasswordPolicyConfigMinLimit(): ?int
    {
        return $this->config[static::PASSWORD_POLICY_ATTRIBUTE_MIN] ?? null;
    }
}
