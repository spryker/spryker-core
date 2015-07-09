<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User;

use SprykerFeature\Shared\User\UserConfig as UserSharedConfig;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class UserConfig extends AbstractBundleConfig
{

    const KEY_INSTALLER_DATA = 'installer_data';

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
        $users = $this->get(UserSharedConfig::USER_SYSTEM_USERS);

        return $users;
    }

}
