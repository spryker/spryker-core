<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\SequenceNumber\SequenceNumberConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Customer\CustomerConfig getSharedConfig()
 */
class CustomerConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    public const ERROR_CODE_CUSTOMER_ALREADY_REGISTERED = 4001;

    /**
     * @var int
     */
    public const ERROR_CODE_CUSTOMER_INVALID_EMAIL = 4002;

    /**
     * @uses \Spryker\Zed\Customer\Communication\Plugin\Mail\CustomerRegistrationMailTypePlugin::MAIL_TYPE
     *
     * @var string
     */
    public const CUSTOMER_REGISTRATION_MAIL_TYPE = 'customer registration mail';

    /**
     * @var string
     */
    public const CUSTOMER_REGISTRATION_WITH_CONFIRMATION_MAIL_TYPE = 'customer registration confirmation mail';

    /**
     * Specification:
     * - Regular expression to validate Customer First Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_FIRST_NAME = '/^[^:\/<>]+$/';

    /**
     * Specification:
     * - Regular expression to validate Customer Last Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_LAST_NAME = '/^[^:\/<>]+$/';

    /**
     * @var int
     */
    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 1;

    /**
     * @uses \Symfony\Component\Security\Core\Encoder\NativePasswordEncoder::MAX_PASSWORD_LENGTH
     *
     * @var int
     */
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 72;

    /**
     * @var int
     */
    protected const ERROR_CODE_CUSTOMER_INVALID_SALUTATION = 4003;

    /**
     * @var string
     */
    protected const REGISTRATION_CONFIRMATION_TOKEN_URL_FALLBACK = '/register/confirm?token=%s&_store=%s';

    /**
     * @var string
     */
    protected const REGISTRATION_CONFIRMATION_TOKEN_URL_FALLBACK_WITHOUT_STORE = '/register/confirm?token=%s';

    /**
     * @var string
     */
    protected const PASSWORD_RESTORE_TOKEN_URL = '%s/password/restore?token=%s&_store=%s';

    /**
     * @var string
     */
    protected const PASSWORD_RESTORE_TOKEN_URL_WITHOUT_STORE = '%s/password/restore?token=%s';

    /**
     * @api
     *
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(CustomerConstants::BASE_URL_YVES);
    }

    /**
     * @api
     *
     * @param string $token
     * @param string|null $storeName
     *
     * @return string
     */
    public function getCustomerPasswordRestoreTokenUrl($token, ?string $storeName = null): string
    {
        if ($storeName === null) {
            return sprintf(static::PASSWORD_RESTORE_TOKEN_URL_WITHOUT_STORE, $this->getHostYves(), $token);
        }

        return sprintf(static::PASSWORD_RESTORE_TOKEN_URL, $this->getHostYves(), $token, $storeName);
    }

    /**
     * Specification:
     * - Provides a registration confirmation token url.
     *
     * @api
     *
     * @param string $token
     * @param string|null $storeName
     *
     * @return string
     */
    public function getRegisterConfirmTokenUrl($token, ?string $storeName = null): string
    {
        if ($storeName === null) {
            $fallback = $this->getHostYves() . static::REGISTRATION_CONFIRMATION_TOKEN_URL_FALLBACK_WITHOUT_STORE;

            return sprintf($this->get(CustomerConstants::REGISTRATION_CONFIRMATION_TOKEN_URL, $fallback), $token);
        }

        $fallback = $this->getHostYves() . static::REGISTRATION_CONFIRMATION_TOKEN_URL_FALLBACK;

        return sprintf($this->get(CustomerConstants::REGISTRATION_CONFIRMATION_TOKEN_URL, $fallback), $token, $storeName);
    }

    /**
     * @api
     *
     * @param string|null $sequenceNumberPrefix
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getCustomerReferenceDefaults(?string $sequenceNumberPrefix = null)
    {
        if (!$sequenceNumberPrefix) {
            $storeName = $this->getStoreName();
        }

        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(CustomerConstants::NAME_CUSTOMER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = $sequenceNumberPrefix;
        $sequenceNumberPrefixParts[] = $this->get(SequenceNumberConstants::ENVIRONMENT_PREFIX, '');
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * Specification:
     * - Provides a prefix used during customer reference generation.
     *
     * @api
     *
     * @return string|null
     */
    public function getCustomerSequenceNumberPrefix(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Provides regular expression for character set password validation.
     *
     * @api
     *
     * @return string
     */
    public function getCustomerPasswordCharacterSet(): string
    {
        return '/^.*$/';
    }

    /**
     * This method provides list of URLs to render blocks inside customer detail page.
     * URL defines path to external bundle controller. For example: /sales/customer/customer-orders would call sales bundle, customer controller, customerOrders action.
     *
     * example:
     * [
     *    'sales' => '/sales/customer/customer-orders',
     * ]
     *
     * @api
     *
     * @return array<string>
     */
    public function getCustomerDetailExternalBlocksUrls()
    {
        return [];
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCustomerPasswordMinLength(): int
    {
        return static::MIN_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCustomerPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * Specification:
     * - Provides a list of strings that will be accepted as a password for customer bypassing any policy validations.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCustomerPasswordAllowList(): array
    {
        return [];
    }

    /**
     * Specification:
     * - A common list of insecure, invalid passwords.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCustomerPasswordDenyList(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Provides a limit for character repeating if defined.
     *
     * Example
     * - Limit=4, forbids to use "aaaa" in password, but allows "aaa"
     *
     * @api
     *
     * @return int|null
     */
    public function getCustomerPasswordSequenceLimit(): ?int
    {
        return null;
    }

    /**
     * Specification:
     * - Enables password check for CustomerFacade::restorePassword() method.
     *
     * @api
     *
     * @deprecated Method is introduced for BC reasons only and will be removed without replacement
     *
     * @return bool
     */
    public function isRestorePasswordValidationEnabled(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @uses \Spryker\Shared\Customer\CustomerConfig::isDoubleOptInEnabled()
     *
     * @return bool
     */
    public function isDoubleOptInEnabled(): bool
    {
        return $this->getSharedConfig()->isDoubleOptInEnabled();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCustomerInvalidSalutationErrorCode(): int
    {
        return static::ERROR_CODE_CUSTOMER_INVALID_SALUTATION;
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return string
     */
    protected function getStoreName(): string
    {
        return Store::getInstance()->getStoreName();
    }
}
