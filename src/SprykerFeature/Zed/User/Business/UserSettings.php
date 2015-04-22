<?php

namespace SprykerFeature\Zed\User\Business;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\User\UserConfig;

class UserSettings
{
    const KEY_INSTALLER_DATA = 'installer_data';

    /**
     * @return array
     */
    public function getSystemUsers()
    {
        $systemUser = [];
        $users = Config::get(UserConfig::USER_SYSTEM_USERS);

        foreach ($users as $username) {
            $systemUser[] = $username;
        }

        return $systemUser;
    }

    public function getInstallerUsers()
    {
        return [
            [
                "firstName" => "Admin",
                "lastName" => "Spryker",
                "username" => "admin@spryker.com",
                "password" => "change123"
            ]
        ];
    }
}
