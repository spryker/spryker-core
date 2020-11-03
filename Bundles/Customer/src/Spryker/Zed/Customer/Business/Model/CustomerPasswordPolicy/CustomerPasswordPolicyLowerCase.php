<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyLowerCase extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ERROR_LOWER_CASE = 'customer.password.error.lower_case';

    public const PASSWORD_POLICY_CHARSET_LOWER_CASE = '/\p{Ll}+/';

    /**
     * @var bool
     */
    protected $lowerCaseRequired;

    /**
     * @param bool $lowerCaseRequired
     */
    public function __construct(bool $lowerCaseRequired)
    {
        $this->lowerCaseRequired = $lowerCaseRequired;
    }

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
        if ($this->lowerCaseRequired && !preg_match(static::PASSWORD_POLICY_CHARSET_LOWER_CASE, $password)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_LOWER_CASE);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
