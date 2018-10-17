<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\Installer;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleConfig;

class Installer implements InstallerInterface
{
    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface
     */
    protected $aclFacade;

    /**
     * @var \Spryker\Zed\UserLocale\UserLocaleConfig
     */
    protected $userLocaleConfig;

    /**
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface $userFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface $localeFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface $aclFacade
     * @param \Spryker\Zed\UserLocale\UserLocaleConfig $userLocaleConfig
     */
    public function __construct(
        UserLocaleToUserBridgeInterface $userFacade,
        UserLocaleToLocaleBridgeInterface $localeFacade,
        UserLocaleToAclBridgeInterface $aclFacade,
        UserLocaleConfig $userLocaleConfig
    ) {
        $this->userFacade = $userFacade;
        $this->localeFacade = $localeFacade;
        $this->aclFacade = $aclFacade;
        $this->userLocaleConfig = $userLocaleConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $userTransfers = $this->createUsers();
        $this->addUsersToRootGroup($userTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer[]
     */
    protected function createUsers(): array
    {
        $userTransfers = [];

        foreach ($this->userLocaleConfig->getInstallerUsers() as $user) {
            $userTransfer = new UserTransfer();
            $userTransfer->setFirstName($user['firstName']);
            $userTransfer->setLastName('lastName');
            $userTransfer->setPassword('password');
            $userTransfer->setFkLocale($this->getLocaleId($user['locale']));
            $userTransfer->setUsername('userName');

            $userTransfers[] = $this->userFacade->createUser($userTransfer);
        }

        return $userTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer[] $userTransfers
     *
     * @return void
     */
    protected function addUsersToRootGroup(array $userTransfers): void
    {
        $rootGroup = $this->aclFacade->getGroupByName(AclConstants::ROOT_GROUP);

        foreach ($userTransfers as $userTransfer) {
            $this->aclFacade->addUserToGroup($userTransfer->getIdUser(), $rootGroup->getIdAclGroup());
        }
    }

    /**
     * @param string $localeCode
     *
     * @return int
     */
    protected function getLocaleId(string $localeCode): int
    {
        return $this->localeFacade->getLocale($localeCode)->getIdLocale();
    }
}
