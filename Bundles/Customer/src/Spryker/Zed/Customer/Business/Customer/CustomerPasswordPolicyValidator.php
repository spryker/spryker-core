<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CustomerPasswordPolicyValidator implements CustomerPasswordPolicyValidatorInterface
{
    public const PASSWORD_POLICY_LENGTH = 'length';
    public const PASSWORD_POLICY_CHARSET = 'charset';
    public const PASSWORD_POLICY_BLACKLIST = 'blacklist';
    public const PASSWORD_POLICY_SEQUENCE = 'sequence';

    public const PASSWORD_POLICY_ATTRIBUTE_SEQUENCE_LIMIT = 'limit';
    public const PASSWORD_POLICY_ATTRIBUTE_MIN = 'min';
    public const PASSWORD_POLICY_ATTRIBUTE_MAX = 'max';
    public const PASSWORD_POLICY_ATTRIBUTE_CHARSET_REQUIRED = 'required';
    public const PASSWORD_POLICY_ATTRIBUTE_SPECIAL_SET = 'specialset';

    public const PASSWORD_POLICY_CHARSET_DIGIT = 'digit';
    public const PASSWORD_POLICY_CHARSET_SPECIAL = 'special';
    public const PASSWORD_POLICY_CHARSET_LOWER_CASE = 'lower';
    public const PASSWORD_POLICY_CHARSET_UPPER_CASE = 'upper';

    public const PASSWORD_POLICY_ERROR_DIGIT = 'customer.password.error.digit';
    public const PASSWORD_POLICY_ERROR_LOWER_CASE = 'customer.password.error.lower_case';
    public const PASSWORD_POLICY_ERROR_UPPER_CASE = 'customer.password.error.upper_case';
    public const PASSWORD_POLICY_ERROR_SPECIAL = 'customer.password.error.special';
    public const PASSWORD_POLICY_ERROR_CHARACTER_INVALID = 'customer.password.error.invalid';
    public const PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';
    public const PASSWORD_POLICY_ERROR_BLACKLIST = 'customer.password.error.blacklist';
    public const PASSWORD_POLICY_ERROR_MIN = 'customer.password.error.min_length';
    public const PASSWORD_POLICY_ERROR_MAX = 'customer.password.error.max_length';

    protected const GLOSSARY_PARAM_VALIDATION_LENGTH = '%limit%';
    protected const GLOSSARY_PARAM_SPECIAL_LIST = '%specialCharsList%';
    protected const GLOSSARY_PARAM_SEQUENCE_LENGTH_LIMIT = '%limit%';
    protected const LOWER_CASE_PATTERN = '/[a-z]/';
    protected const UPPER_CASE_PATTERN = '/[A-Z]/';
    protected const NUMBER_PATTERN = '/(\d)/';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateLength(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer {
        $passwordLength = strlen($password);

        if ($passwordLength < $config[static::PASSWORD_POLICY_ATTRIBUTE_MIN]) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MIN);
        }

        if ($passwordLength > $config[static::PASSWORD_POLICY_ATTRIBUTE_MAX]) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_MAX);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string[][] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateCharset(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer {
        $requiredCharsets = $config[static::PASSWORD_POLICY_ATTRIBUTE_CHARSET_REQUIRED];
        /** @var string $specialChars */
        $specialChars = $config[static::PASSWORD_POLICY_ATTRIBUTE_SPECIAL_SET];

        if (in_array(static::PASSWORD_POLICY_CHARSET_DIGIT, $requiredCharsets)) {
            if (!$this->hasNumber($password)) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_DIGIT);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_LOWER_CASE, $requiredCharsets)) {
            if (!$this->hasLowerCase($password)) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_LOWER_CASE);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_UPPER_CASE, $requiredCharsets)) {
            if (!$this->hasUpperCase($password)) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_UPPER_CASE);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_SPECIAL, $requiredCharsets)) {
            if (!$this->hasSpecial($password, $specialChars)) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_SPECIAL);
            }
        }

        if (!$this->checkAllowed($password, $specialChars)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_CHARACTER_INVALID);
        }

        return $customerResponseTransfer;
    }

    /**
     * @inheriDoc
     *
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateSequence(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer {
        $sequenceLengthLimit = $config[static::PASSWORD_POLICY_ATTRIBUTE_SEQUENCE_LIMIT];
        $previousChar = '';
        $counter = 1;
        foreach (str_split($password) as $char) {
            if ($char === $previousChar) {
                $counter++;
            } else {
                $counter = 1;
            }
            if ($sequenceLengthLimit < $counter) {
                $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_SEQUENCE);

                break;
            }
            $previousChar = $char;
        }

        return $customerResponseTransfer;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validateBlacklist(
        string $password,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer {
        if (in_array($password, $config)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_BLACKLIST);
        }

        return $customerResponseTransfer;
    }

    /**
     * Checks if word has at least one number character.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasNumber(string $word): bool
    {
        return (bool)preg_match(static::NUMBER_PATTERN, $word);
    }

    /**
     * Checks if word has at least one character of alphabet in lower case.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasLowerCase(string $word): bool
    {
        return (bool)preg_match(static::LOWER_CASE_PATTERN, $word);
    }

    /**
     * Checks if word has it least one character of alphabet in upper case.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasUpperCase(string $word): bool
    {
        return (bool)preg_match(static::UPPER_CASE_PATTERN, $word);
    }

    /**
     * Checks if word has at least one character from a configured special character set.
     *
     * @param string $word
     * @param string $specialChars
     *
     * @return bool
     */
    protected function hasSpecial(string $word, string $specialChars): bool
    {
        foreach (str_split($word) as $char) {
            if (strpos($specialChars, $char) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if word has only allowed characters (a-z + A-Z + configured set of special characters).
     *
     * @param string $word
     * @param string $specialCharList
     *
     * @return bool
     */
    protected function checkAllowed(string $word, string $specialCharList): bool
    {
        foreach (str_split($word) as $char) {
            if (
                !$this->hasNumber($char) &&
                !$this->hasLowerCase($char) &&
                !$this->hasUpperCase($char) &&
                !$this->hasSpecial($char, $specialCharList)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Adds error MessageTransfer to CustomerResponseTransfer.
     *
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string $errorMessage
     * @param mixed[] $params
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function addError(
        CustomerResponseTransfer $customerResponseTransfer,
        string $errorMessage,
        array $params = []
    ): CustomerResponseTransfer {
        $messageTransfer = (new CustomerErrorTransfer())
            ->setMessage($errorMessage);

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError($messageTransfer);
    }
}
