<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser;

use Spryker\Shared\SecuritySystemUser\SecuritySystemUserConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecuritySystemUserConfig extends AbstractBundleConfig
{
    public const ROLE_SYSTEM_USER = 'ROLE_SYSTEM_USER';
    public const AUTH_TOKEN = 'Auth-Token';

    /**
     * @uses \Spryker\Shared\Session\SessionConstants::ZED_SESSION_TIME_TO_LIVE
     */
    protected const ZED_SESSION_TIME_TO_LIVE = 'SESSION:ZED_SESSION_TIME_TO_LIVE';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @api
     *
     * @return array
     */
    public function getUsersCredentials()
    {
        $response = [];
        $credentials = $this->get(SecuritySystemUserConstants::AUTH_DEFAULT_CREDENTIALS);

        /** @var string $username */
        foreach ($this->getSystemUsers() as $username) {
            if (isset($credentials[$username])) {
                $response[$username] = $credentials[$username];
            }
        }

        return $response;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getSystemUserSessionRedisLifeTime(): int
    {
        return (int)$this->get(
            SecuritySystemUserConstants::SYSTEM_USER_SESSION_REDIS_LIFE_TIME,
            $this->get(static::ZED_SESSION_TIME_TO_LIVE)
        );
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @api
     *
     * @return array
     */
    protected function getSystemUsers()
    {
        return $this->get(SecuritySystemUserConstants::USER_SYSTEM_USERS);
    }
}
