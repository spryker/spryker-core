<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';

    /**
     * @var string
     */
    protected const HOME_PATH = '/';

    /**
     * @var string
     */
    protected const LOGIN_PATH = '/security-gui/login';

    /**
     * @var string
     */
    protected const PASSWORD_RESET_PATH = '/security-gui/password/reset';

    /**
     * @var string
     */
    protected const BACKOFFICE_ROUTE_PATTERN = '^/';

    /**
     * @var string
     */
    protected const IGNORABLE_ROUTE_PATTERN = '^/security-gui';

    /**
     * @uses \Spryker\Zed\User\UserConfig::MIN_LENGTH_USER_PASSWORD
     *
     * @var int
     */
    protected const MIN_LENGTH_USER_PASSWORD = 12;

    /**
     * @uses \Spryker\Zed\User\UserConfig::MAX_LENGTH_USER_PASSWORD
     *
     * @var int
     */
    protected const MAX_LENGTH_USER_PASSWORD = 128;

    /**
     * @uses \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig::BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE
     *
     * @var string
     */
    protected const BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE = 'back-office-user';

    /**
     * @var bool
     */
    protected const IS_BACKOFFICE_USER_SECURITY_BLOCKER_ENABLED = false;

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
    public function isBackofficeUserSecurityBlockerEnabled(): bool
    {
        return static::IS_BACKOFFICE_USER_SECURITY_BLOCKER_ENABLED;
    }

    /**
     * Specification:
     * - Returns the entity identifier that is used to block the password resets.
     *
     * @api
     *
     * @return string
     */
    public function getBackofficeUserSecurityBlockerEntityType(): string
    {
        return static::BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlHome(): string
    {
        return static::HOME_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_PATH;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getIgnorablePaths(): ?string
    {
        return static::IGNORABLE_ROUTE_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBackofficeRoutePattern(): string
    {
        return static::BACKOFFICE_ROUTE_PATTERN;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getUserPasswordMinLength(): int
    {
        return static::MIN_LENGTH_USER_PASSWORD;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getUserPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_USER_PASSWORD;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getDefaultBackofficeAuthenticationRoles(): array
    {
        return [static::ROLE_BACK_OFFICE_USER];
    }

    /**
     * Specification:
     * - Returns the pattern for user password validation.
     *
     * @api
     *
     * @return string
     */
    public function getUserPasswordPattern(): string
    {
        return static::PASSWORD_VALIDATION_PATTERN;
    }

    /**
     * Specification:
     * - Returns the message for user password validation.
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
