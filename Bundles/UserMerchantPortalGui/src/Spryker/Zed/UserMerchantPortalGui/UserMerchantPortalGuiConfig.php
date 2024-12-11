<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var bool
     */
    protected const IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED = false;

    /**
     * @var bool
     */
    protected const IS_SECURITY_BLOCKER_FOR_MERCHANT_USER_EMAIL_CHANGING_ENABLED = false;

    /**
     * @uses \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig::MERCHANT_PORTAL_USER_ENTITY_TYPE
     *
     * @var string
     */
    protected const ENTITY_TYPE_MERCHANT_PORTAL_USER = 'merchant-portal-user';

    /**
     * @var int
     */
    protected const MIN_LENGTH_MERCHANT_USER_PASSWORD = 12;

    /**
     * @var int
     */
    protected const MAX_LENGTH_MERCHANT_USER_PASSWORD = 128;

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_PATTERN = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~])[A-Za-z\d!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~]+$/';

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_MESSAGE = 'Your password must include at least one uppercase letter, one lowercase letter, one number, and one special character from the following list: !@#$%^&*()_-+=[]{}|;:<>.,/?\~. Non-Latin and other special characters are not allowed.';

    /**
     * Specification:
     * - Returns whether email update should be protected with password validation.
     *
     * @api
     *
     * @return bool
     */
    public function isEmailUpdatePasswordVerificationEnabled(): bool
    {
        return static::IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED;
    }

    /**
     * Specification:
     * - Defines whether merchant user email change is protected by security blocker functionality.
     *
     * @api
     *
     * @return bool
     */
    public function isSecurityBlockerForMerchantUserEmailChangingEnabled(): bool
    {
        return static::IS_SECURITY_BLOCKER_FOR_MERCHANT_USER_EMAIL_CHANGING_ENABLED;
    }

    /**
     * Specification:
     * - Returns merchant portal user entity type for security blocker functionality.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerMerchantPortalUserEntityType(): string
    {
        return static::ENTITY_TYPE_MERCHANT_PORTAL_USER;
    }

    /**
     * Specification:
     * - Returns the minimum length for merchant user password.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantUserPasswordMinLength(): int
    {
        return static::MIN_LENGTH_MERCHANT_USER_PASSWORD;
    }

    /**
     * Specification:
     * - Returns the maximum length for merchant user password.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantUserPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_MERCHANT_USER_PASSWORD;
    }

    /**
     * Specification:
     * - Returns the pattern for merchant user password validation.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantUserPasswordPattern(): string
    {
        return static::PASSWORD_VALIDATION_PATTERN;
    }

    /**
     * Specification:
     * - Returns the message for merchant user password validation.
     *
     * @api
     *
     * @return string
     */
    public function getPasswordValidationMessage(): string
    {
        return static::PASSWORD_VALIDATION_MESSAGE;
    }
}
