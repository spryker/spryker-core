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
     * @var int
     */
    protected $passwordLengthMin;

    /**
     * @var int
     */
    protected $passwordLengthMax;

    /**
     * @param string[] $config
     */
    public function __construct(array $config)
    {
        $this->passwordLengthMin = $config[static::PASSWORD_POLICY_ATTRIBUTE_MIN] ?? 0;
        $this->passwordLengthMax = $config[static::PASSWORD_POLICY_ATTRIBUTE_MAX] ?? 0;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        $passwordLength = mb_strlen($password);
        if ($this->passwordLengthMin && $passwordLength < $this->passwordLengthMin) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MIN);
        }

        if ($this->passwordLengthMax && $passwordLength > $this->passwordLengthMax) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MAX);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
