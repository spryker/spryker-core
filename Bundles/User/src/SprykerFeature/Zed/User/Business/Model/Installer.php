<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Business\Model;

use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use SprykerFeature\Zed\User\UserConfig;

class Installer implements InstallerInterface
{

    /**
     * @var UserQueryContainer
     */
    protected $queryContainer;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var UserConfig
     */
    protected $settings;

    /**
     * @param UserQueryContainer $queryContainer
     * @param UserInterface $user
     * @param UserConfig $settings
     */
    public function __construct(
        UserQueryContainer $queryContainer,
        UserInterface $user,
        UserConfig $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->user = $user;
        $this->settings = $settings;
    }

    /**
     * Main Installer Method
     */
    public function install()
    {
        $this->addUsers($this->settings->getInstallerUsers());
    }

    protected function addUsers(array $usersArray)
    {
        foreach ($usersArray as $user) {
            if (!$this->user->hasUserByUsername($user['username'])) {
                $this->user->addUser(
                    $user['firstName'],
                    $user['lastName'],
                    $user['username'],
                    $user['password']
                );
            }
        }
    }

}
