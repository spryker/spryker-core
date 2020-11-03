<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicySequence extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ATTRIBUTE_LIMIT = 'limit';

    public const PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';

    /**
     * @var int
     */
    protected $sequenceLengthLimit;

    /**
     * @param bool[] $config
     */
    public function __construct(array $config)
    {
        $this->sequenceLengthLimit = $config[static::PASSWORD_POLICY_ATTRIBUTE_LIMIT] ?? -1;
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
        if ($this->sequenceLengthLimit < 0) {
            return $this->proceed($password, $customerResponseTransfer);
        }
        $counter = 0;
        $prevChar = '';
        $encoding = mb_internal_encoding();
        foreach (mb_str_split($password, '') as $char) {
            if ($char === $prevChar) {
                $counter++;
            }
            if ($this->sequenceLengthLimit < $counter) {
                $this->addError($customerResponseTransfer, self::PASSWORD_POLICY_ERROR_SEQUENCE);

                break;
            }
            $prevChar = $char;
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
