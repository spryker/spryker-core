<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicySpecial extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ERROR_SPECIAL = 'customer.password.error.special';

    public const PASSWORD_POLICY_CHARSET_SPECIAL = '/[^(\p{N}|\p{L})+]/';

    /**
     * @var bool
     */
    protected $specialRequired;

    /**
     * @param bool $specialRequired
     */
    public function __construct(bool $specialRequired = false)
    {
        $this->specialRequired = $specialRequired;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if ($this->specialRequired && !preg_match(static::PASSWORD_POLICY_CHARSET_SPECIAL, $password)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_SPECIAL);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
