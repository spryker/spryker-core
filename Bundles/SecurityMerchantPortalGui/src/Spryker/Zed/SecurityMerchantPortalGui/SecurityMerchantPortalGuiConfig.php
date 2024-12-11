<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ROLE_MERCHANT_USER = 'ROLE_MERCHANT_USER';

    /**
     * @var string
     */
    protected const MERCHANT_USER_DEFAULT_URL = '/dashboard-merchant-portal-gui/dashboard';

    /**
     * @var string
     */
    protected const LOGIN_URL = '/security-merchant-portal-gui/login';

    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_SECURITY_BLOCKER_ENTITY_TYPE = 'customer';

    /**
     * @var bool
     */
    protected const MERCHANT_PORTAL_SECURITY_BLOCKER_ENABLED = false;

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
     * - Checks if the security blocker is enabled.
     * - It is disabled by default.
     *
     * @api
     *
     * @return bool
     */
    public function isMerchantPortalSecurityBlockerEnabled(): bool
    {
        return static::MERCHANT_PORTAL_SECURITY_BLOCKER_ENABLED;
    }

    /**
     * Specification:
     * - Returns the entity identifier that is used to block the password resets.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalSecurityBlockerEntityType(): string
    {
        return static::MERCHANT_PORTAL_SECURITY_BLOCKER_ENTITY_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultTargetPath(): string
    {
        return static::MERCHANT_USER_DEFAULT_URL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_URL;
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
