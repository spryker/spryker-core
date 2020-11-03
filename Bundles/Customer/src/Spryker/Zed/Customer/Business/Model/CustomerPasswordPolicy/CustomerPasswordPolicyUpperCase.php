<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyUpperCase extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ERROR_UPPER_CASE = 'customer.password.error.upper_case';

    public const PASSWORD_POLICY_CHARSET_UPPER_CASE = '/\p{Lu}+/';

    /**
     * @var bool
     */
    protected $upperCaseRequired;

    /**
     * @param bool $config
     */
    public function __construct(bool $upperCaseRequired)
    {
        $this->upperCaseRequired = $upperCaseRequired;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if ($this->upperCaseRequired && preg_match(static::PASSWORD_POLICY_CHARSET_UPPER_CASE, $password)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_UPPER_CASE);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
