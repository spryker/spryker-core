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
    protected const DAY_IN_SECONDS = 86400;

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::PASSWORD_RESET_PATH
     */
    protected const PASSWORD_RESET_PATH = '/security-gui/password/reset';

    /**
     * @api
     *
     * @return int
     */
    public function getPasswordTokenExpirationInSeconds(): int
    {
        return static::DAY_IN_SECONDS;
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
}
