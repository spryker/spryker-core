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
    public const KEY_INSTALLER_DATA = 'installer_data';

    /**
     * @return array
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
     * @return array
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
     * @return array
     */
    private function getUserFromGlobalConfig()
    {
        $users = $this->get(UserConstants::USER_SYSTEM_USERS);

        return $users;
    }
}
