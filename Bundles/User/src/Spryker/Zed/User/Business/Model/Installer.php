<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;
use Spryker\Zed\User\UserConfig;

class Installer implements InstallerInterface
{
    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\User\Business\Model\UserInterface
     */
    protected $user;

    /**
     * @var \Spryker\Zed\User\UserConfig
     */
    protected $settings;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\User\Business\Model\UserInterface $user
     * @param \Spryker\Zed\User\UserConfig $settings
     */
    public function __construct(
        UserQueryContainerInterface $queryContainer,
        UserInterface $user,
        UserConfig $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->user = $user;
        $this->settings = $settings;
    }

    /**
     * Main Installer Method
     *
     * @return void
     */
    public function install()
    {
        $this->addUsers($this->settings->getInstallerUsers());
    }

    /**
     * @param array $usersArray
     *
     * @return void
     */
    protected function addUsers(array $usersArray)
    {
        foreach ($usersArray as $user) {
            if ($this->user->hasUserByUsername($user['username'])) {
                continue;
            }

            $userTransfer = (new UserTransfer())->fromArray($user, true);

            $this->user->createUser($userTransfer);
        }
    }
}
