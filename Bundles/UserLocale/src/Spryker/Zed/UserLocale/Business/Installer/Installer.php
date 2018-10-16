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

class Installer implements InstallerInterface
{
    protected const USER_FIRST_NAME = 'Admin';
    protected const USER_LAST_NAME = 'German';
    protected const USER_PASSWORD = 'Change123';
    protected const USER_USERNAME = 'admin_de@spryker.com';
    protected const LOCALE_CODE = 'de_DE';

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
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface $userFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface $localeFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToAclBridgeInterface $aclFacade
     */
    public function __construct(
        UserLocaleToUserBridgeInterface $userFacade,
        UserLocaleToLocaleBridgeInterface $localeFacade,
        UserLocaleToAclBridgeInterface $aclFacade
    ) {
        $this->userFacade = $userFacade;
        $this->localeFacade = $localeFacade;
        $this->aclFacade = $aclFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $userTransfer = $this->createUser();
        $this->addUserToRootGroup($userTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUser(): UserTransfer
    {
        $userTransfer = new UserTransfer();
        $userTransfer->setFirstName(static::USER_FIRST_NAME);
        $userTransfer->setLastName(static::USER_LAST_NAME);
        $userTransfer->setPassword(static::USER_PASSWORD);
        $userTransfer->setFkLocale($this->getLocaleId());
        $userTransfer->setUsername(static::USER_USERNAME);

        return $this->userFacade->createUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function addUserToRootGroup(UserTransfer $userTransfer): void
    {
        $rootGroup = $this->aclFacade->getGroupByName(AclConstants::ROOT_GROUP);

        $this->aclFacade->addUserToGroup($userTransfer->getIdUser(), $rootGroup->getIdAclGroup());
    }

    /**
     * @return int
     */
    protected function getLocaleId(): int
    {
        return $this->localeFacade->getLocale(static::LOCALE_CODE)->getIdLocale();
    }
}
