<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\Installer;

use Generated\Shared\Transfer\UserTransfer;
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
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserBridgeInterface $userFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleBridgeInterface $localeFacade
     */
    public function __construct(UserLocaleToUserBridgeInterface $userFacade, UserLocaleToLocaleBridgeInterface $localeFacade)
    {
        $this->userFacade = $userFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->createUser();
    }

    /**
     * @return void
     */
    protected function createUser(): void
    {
        $userTransfer = new UserTransfer();
        $userTransfer->setFirstName(static::USER_FIRST_NAME);
        $userTransfer->setLastName(static::USER_LAST_NAME);
        $userTransfer->setPassword(static::USER_PASSWORD);
        $userTransfer->setFkLocale($this->getLocaleId());
        $userTransfer->setUsername(static::USER_USERNAME);

        $this->userFacade->createUser($userTransfer);
    }

    /**
     * @return int
     */
    protected function getLocaleId(): int
    {
        return $this->localeFacade->getLocale(static::LOCALE_CODE)->getIdLocale();
    }
}
