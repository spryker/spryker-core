<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyForbidden extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ATTRIBUTE_FORBIDDEN = 'forbidden';

    public const PASSWORD_POLICY_ERROR_FORBIDDEN = 'customer.password.error.forbidden';

    /**
     * @var string
     */
    protected $forbiddenCharacters;

    /**
     * @param string[] $config
     */
    public function __construct(array $config)
    {
        $this->forbiddenCharacters = $config[static::PASSWORD_POLICY_ATTRIBUTE_FORBIDDEN] ?? '';
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (empty($this->config)) {
            return $this->proceed($password, $customerResponseTransfer);
        }

        foreach (mb_str_split($password) as $character) {
            if (strpos($character, $this->forbiddenCharacters) !== false) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_FORBIDDEN);

                break;
            }
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
