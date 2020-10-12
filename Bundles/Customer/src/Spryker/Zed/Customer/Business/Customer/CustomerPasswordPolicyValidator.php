<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CustomerPasswordPolicyValidator implements CustomerPasswordPolicyValidatorInterface
{
    public const PASSWORD_POLICY_LENGTH = 'length';
    public const PASSWORD_POLICY_CHARSET = 'charset-list';
    public const PASSWORD_POLICY_BLACKLIST = 'blacklist';
    public const PASSWORD_POLICY_SEQUENCE = 'sequence';
    public const PASSWORD_POLICY_ATTRIBUTE_MIN = 'min';
    public const PASSWORD_POLICY_ATTRIBUTE_MAX = 'max';
    public const PASSWORD_POLICY_ATTRIBUTE_CHARSET = 'charset';
    public const PASSWORD_POLICY_ATTRIBUTE_SPECIAL = 'spec';
    public const PASSWORD_POLICY_ATTRIBUTE_LOWER_CASE = 'lower';
    public const PASSWORD_POLICY_ATTRIBUTE_UPPER_CASE = 'upper';
    public const PASSWORD_POLICY_ATTRIBUTE_SPECIALSET = 'specialset';
    public const PASSWORD_POLICY_CHARSET_LOWER = '/[a-z]/';
    public const PASSWORD_POLICY_CHARSET_UPPER = '/[A-Z]/';
    public const PASSWORD_POLICY_ERROR_LOWER_CASE = 'customer.password.error.lower_case';
    public const PASSWORD_POLICY_ERROR_UPPER_CASE = 'customer.password.error.upper_case';
    public const PASSWORD_POLICY_ERROR_SPECIAL = 'customer.password.error.special';
    public const PASSWORD_POLICY_ERROR_INVALID = 'customer.password.error.invalid';
    public const PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';
    public const PASSWORD_POLICY_ERROR_BLACKLIST = 'customer.password.error.blacklist';
    public const PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';
    public const PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

    /**
     * @inheriDoc
     */
    public function checkLength(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $config): CustomerPasswordPolicyResultTransfer
    {
        $passwordLength = strlen($customerTransfer->getPassword());

        if ($passwordLength < $config[self::PASSWORD_POLICY_ATTRIBUTE_MIN]) {
            $this->addError(
                $resultTransfer,
                self::PASSWORD_POLICY_ERROR_MIN
            );
        }

        if ($passwordLength > $config[self::PASSWORD_POLICY_ATTRIBUTE_MAX]) {
            $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_MAX);
        }

        return $resultTransfer;
    }


    /**
     * @inheriDoc
     */
    public function checkCharset(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $config): CustomerPasswordPolicyResultTransfer
    {
        $password = $customerTransfer->getPassword();
        $requiredCharsets = $config[self::PASSWORD_POLICY_ATTRIBUTE_CHARSET];
        $specialChars = $config[self::PASSWORD_POLICY_ATTRIBUTE_SPECIALSET];

        if (in_array(self::PASSWORD_POLICY_ATTRIBUTE_LOWER_CASE, $requiredCharsets)) {
            if (!$this->hasLowerCase($password)) {
                $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_LOWER_CASE);
            }
        }

        if (in_array(self::PASSWORD_POLICY_ATTRIBUTE_UPPER_CASE, $requiredCharsets)) {
            if (!$this->hasUpperCase($password)) {
                $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_LOWER_CASE);
            }
        }

        if (in_array(self::PASSWORD_POLICY_ATTRIBUTE_SPECIAL, $requiredCharsets)) {
            if (!$this->hasSpecial($password, $specialChars)) {
                $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_LOWER_CASE);
            }
        }

        if (!$this->checkAllowed($password, $specialChars)) {
            $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_SEQUENCE);
        }

        return $resultTransfer;
    }

    /**
     * @inheriDoc
     */
    public function checkSequence(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, int $sequenceLengthLimit): CustomerPasswordPolicyResultTransfer
    {
        $prevchar;
        $counter = 0;
        foreach ($customerTransfer->getPassword() as $char) {
            if ($char === $prevchar) {
                $counter++;
            }
            if ($sequenceLengthLimit < $counter) {
                $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_SEQUENCE);
                break;
            }
            $prevchar = $char;
        }

        return $resultTransfer;
    }

    /**
     * @inheriDoc
     */
    public function checkBlacklist(CustomerTransfer $customerTransfer, CustomerPasswordPolicyResultTransfer $resultTransfer, array $blackList): CustomerPasswordPolicyResultTransfer
    {
        if (in_array($customerTransfer->getPassword(), $blackList)) {
            $this->addError($resultTransfer, self::PASSWORD_POLICY_ERROR_BLACKLIST);
        }

        return $resultTransfer;
    }

    /**
     * Checks if word has at least one character of aplhabet in lower case.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasLowerCase(string $word): bool
    {
        return (bool) preg_match(self::PASSWORD_POLICY_CHARSET_LOWER);
    }

    /**
     * Checks if word has it least one character of aplhabet in upper case.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasUpperCase(string $word): bool
    {
        return (bool) preg_match(self::PASSWORD_POLICY_CHARSET_UPPER);
    }

    /**
     * Checks if word has at least one character from a configured special character set.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasSpecial(string $word, array $specialCharList): bool
    {
        foreach ($word as $char) {
            if (strpos(implode('', $specialCharList), $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if word has only allowed characters (a-z + A-Z + configured set of special characters).
     *
     * @param string $word
     * @param array $specialCharList
     *
     * @return bool
     */
    protected function checkAllowed(string $word, array $specialCharList): bool
    {
        foreach ($word as $char) {
            if (!$this->hasLowerCase($char) &&
                !$this->hasUpperCase($char) &&
                !$this->hasSpecial($char, $specialCharList)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Adds error MessageTransfer to CustomerPasswordPolicyResultTransfer.
     *
     * @param Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string $errorMessage
     *
     * @return CustomerPasswordPolicyResultTransfer
     */
    protected function addError(CustomerPasswordPolicyResultTransfer $resultTransfer, string $errorMessage)
    {
        $message = new MessageTransfer();
        $message->setValue($errorMessage);

        return $resultTransfer->setIsSuccessful(false)->addMessage($message);
    }
}
