<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User;

use Spryker\Shared\User\UserConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const KEY_INSTALLER_DATA = 'installer_data';

    /**
     * @var int
     */
    protected const MIN_LENGTH_USER_PASSWORD = 12;

    /**
     * @var int
     */
    protected const MAX_LENGTH_USER_PASSWORD = 128;

    /**
     * @var bool
     */
    protected const IS_POST_SAVE_PLUGINS_ENABLED_AFTER_USER_STATUS_CHANGE = false;

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_PATTERN = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~])[A-Za-z\d!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~]+$/';

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_MESSAGE = 'Your password must include at least one uppercase letter, one lowercase letter, one number, and one special character from the following list: !@#$%^&*()_-+=[]{}|;:<>.,/?\~. Non-Latin and other special characters are not allowed.';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getSystemUsers()
    {
        $systemUser = [];
        $users = $this->getUserFromGlobalConfig();

        foreach ($users as $username) {
            $systemUser[] = $username;
        }

        return $systemUser;
    }

    /**
     * @api
     *
     * @return array<array<string, mixed>>
     */
    public function getInstallerUsers()
    {
        return [
            [
                'firstName' => 'Admin',
                'lastName' => 'Spryker',
                'username' => 'admin@spryker.com',
                'password' => 'change123',
            ],
        ];
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
     * Specification:
     * - Defines if post save plugins will be executed after user status updating.
     *
     * @api
     *
     * @see {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface}
     *
     * @return bool
     */
    public function isPostSavePluginsEnabledAfterUserStatusChange(): bool
    {
        return static::IS_POST_SAVE_PLUGINS_ENABLED_AFTER_USER_STATUS_CHANGE;
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

    /**
     * @return array<string>
     */
    private function getUserFromGlobalConfig()
    {
        $users = $this->get(UserConstants::USER_SYSTEM_USERS);

        return $users;
    }
}
