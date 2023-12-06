<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset;

use Spryker\Shared\UserPasswordReset\UserPasswordResetConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserPasswordResetConfig extends AbstractBundleConfig
{
    /**
     * Default expiration time in seconds, 2h by default.
     *
     * @var int
     */
    protected const PASSWORD_EXPIRATION_TIME_IN_SECONDS = 7200;

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::PASSWORD_RESET_PATH
     *
     * @var string
     */
    protected const PASSWORD_RESET_PATH = '/security-gui/password/reset';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @api
     *
     * @return int
     */
    public function getPasswordTokenExpirationInSeconds(): int
    {
        return static::PASSWORD_EXPIRATION_TIME_IN_SECONDS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseUrlZed(): string
    {
        return $this->get(UserPasswordResetConstants::BASE_URL_ZED);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPasswordResetPath(): string
    {
        return static::PASSWORD_RESET_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUserStatusActive(): string
    {
        return static::USER_STATUS_ACTIVE;
    }
}
