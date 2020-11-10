<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\CustomerConfig;

class CharacterSetCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_CHARACTER_SET_UPPER_CASE_PATTERN = '/\p{Lu}+/';

    public const PASSWORD_POLICY_CHARACTER_SET_LOWER_CASE_PATTERN = '/\p{Ll}+/';

    public const PASSWORD_POLICY_CHARACTER_SET_SPECIAL_PATTERN = '/[^(\p{N}|\p{L})+]/';

    public const PASSWORD_POLICY_CHARACTER_SET_DIGIT_PATTERN = '/\p{N}+/';

    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DIGIT = 'customer.password.error.digit';

    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_UPPER_CASE = 'customer.password.error.upper_case';

    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_LOWER_CASE = 'customer.password.error.lower_case';

    public const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SPECIAL = 'customer.password.error.special';

    /**
     * @var string[]
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->config = $customerConfig->getCharacterSetCustomerPasswordPolicy();
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        foreach ($this->config as $pattern => $errorMessage) {
            if (preg_match($pattern, $password)) {
                continue;
            }
            $customerErrorTransfer = (new CustomerErrorTransfer())
                ->setMessage($errorMessage);
            $customerResponseTransfer->setIsSuccess(false)
                ->addError($customerErrorTransfer);
        }

        return $customerResponseTransfer;
    }
}
