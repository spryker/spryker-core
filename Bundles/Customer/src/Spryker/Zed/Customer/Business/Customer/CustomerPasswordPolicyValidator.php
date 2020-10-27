<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CustomerPasswordPolicyValidator implements CustomerPasswordPolicyValidatorInterface
{
    public const PASSWORD_POLICY_LENGTH = 'length';
    public const PASSWORD_POLICY_CHARSET = 'charset';
    public const PASSWORD_POLICY_BLACKLIST = 'blacklist';
    public const PASSWORD_POLICY_SEQUENCE = 'blacklist';

    public const PASSWORD_POLICY_ATTRIBUTE_SEQUENCE_LIMIT = 'limit';
    public const PASSWORD_POLICY_ATTRIBUTE_MIN = 'min';
    public const PASSWORD_POLICY_ATTRIBUTE_MAX = 'max';
    public const PASSWORD_POLICY_ATTRIBUTE_CHARSET_REQUIRED = 'required';
    public const PASSWORD_POLICY_ATTRIBUTE_SPECIALSET = 'specialset';

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

    protected const GLOSSARY_PARAM_VALIDATION_LENGTH = '{{ limit }}';
    protected const GLOSSARY_PARAM_VALIDATION_SPECIAL_LIST = '{{ specialCharsList }}';
    protected const GLOSSARY_PARAM_VALIDATION_SEQUENCE_LENGTH_LIMIT = '{{ sequenceLimit }}';
    protected const LOWER_CASE_PATTERN = '/[a-z]/';
    protected const UPPER_CASE_PATTERN = '/[A-Z]/';
    protected const NUMBER_PATTERN = '/(\d)/';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkLength(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer {
        $passwordLength = strlen($password);

        if ($passwordLength < $config[static::PASSWORD_POLICY_ATTRIBUTE_MIN]) {
            $this->addError(
                $resultTransfer,
                static::PASSWORD_POLICY_ERROR_MIN,
                [
                    static::GLOSSARY_PARAM_VALIDATION_LENGTH => $config[static::PASSWORD_POLICY_ATTRIBUTE_MIN],
                ]
            );
        }

        if ($passwordLength > $config[static::PASSWORD_POLICY_ATTRIBUTE_MAX]) {
            $this->addError(
                $resultTransfer,
                static::PASSWORD_POLICY_ERROR_MAX,
                [
                    static::GLOSSARY_PARAM_VALIDATION_LENGTH => $config[static::PASSWORD_POLICY_ATTRIBUTE_MAX],
                ]
            );
        }

        return $resultTransfer;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string[][] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkCharset(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer {
        $requiredCharsets = $config[static::PASSWORD_POLICY_ATTRIBUTE_CHARSET_REQUIRED];
        /** @var string $specialChars */
        $specialChars = $config[static::PASSWORD_POLICY_ATTRIBUTE_SPECIALSET];

        if (in_array(static::PASSWORD_POLICY_CHARSET_DIGIT, $requiredCharsets)) {
            if (!$this->hasNumber($password)) {
                $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_DIGIT);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_LOWER_CASE, $requiredCharsets)) {
            if (!$this->hasLowerCase($password)) {
                $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_LOWER_CASE);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_UPPER_CASE, $requiredCharsets)) {
            if (!$this->hasUpperCase($password)) {
                $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_UPPER_CASE);
            }
        }

        if (in_array(static::PASSWORD_POLICY_CHARSET_SPECIAL, $requiredCharsets)) {
            if (!$this->hasSpecial($password, $specialChars)) {
                $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_SPECIAL);
            }
        }

        if (!$this->checkAllowed($password, $specialChars)) {
            $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_CHARACTER_INVALID);
        }

        return $resultTransfer;
    }

    /**
     * @inheriDoc
     *
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param int[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkSequence(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer {
        $sequenceLengthLimit = $config[static::PASSWORD_POLICY_ATTRIBUTE_SEQUENCE_LIMIT];
        $prevchar = '';
        $counter = 1;
        foreach (str_split($password) as $char) {
            if ($char === $prevchar) {
                $counter++;
            } else {
                $counter = 1;
            }
            if ($sequenceLengthLimit < $counter) {
                $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_SEQUENCE);

                break;
            }
            $prevchar = $char;
        }

        return $resultTransfer;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function checkBlacklist(
        string $password,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer {
        if (in_array($password, $config)) {
            $this->addError($resultTransfer, static::PASSWORD_POLICY_ERROR_BLACKLIST);
        }

        return $resultTransfer;
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
     * Checks if word has at least one character of aplhabet in lower case.
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
     * Checks if word has it least one character of aplhabet in upper case.
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
            if (strpos($specialChars, $char)) {
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
     * Adds error MessageTransfer to CustomerPasswordPolicyResultTransfer.
     *
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string $errorMessage
     * @param mixed[] $params
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    protected function addError(
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        string $errorMessage,
        array $params = []
    ): CustomerPasswordPolicyResultTransfer {
        $message = (new MessageTransfer())
            ->setValue($errorMessage)
            ->setParameters($params);

        return $resultTransfer
            ->setIsSuccessful(false)
            ->addMessage($message);
    }
}
