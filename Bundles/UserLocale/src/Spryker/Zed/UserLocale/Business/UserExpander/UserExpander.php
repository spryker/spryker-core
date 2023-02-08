<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\UserExpander;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;

class UserExpander implements UserExpanderInterface
{
    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface $localeFacade
     */
    public function __construct(
        UserLocaleToLocaleFacadeBridgeInterface $localeFacade
    ) {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithLocale(UserTransfer $userTransfer): UserTransfer
    {
        $idLocale = $userTransfer->getFkLocale();
        $localeName = $userTransfer->getLocaleName();

        if (!$idLocale && !$localeName) {
            $localeTransfer = $this->localeFacade->getCurrentLocale();
            $userTransfer->setFkLocale($localeTransfer->getIdLocale())
                ->setLocaleName($localeName);

            return $userTransfer;
        }

        if (!$idLocale) {
            $localeTransfer = $this->localeFacade->getLocale($localeName);
            $userTransfer->setFkLocale($localeTransfer->getIdLocale())
                ->setLocaleName($localeName);

            return $userTransfer;
        }

        if (!$localeName) {
            $localeTransfer = $this->localeFacade->getLocaleById($idLocale);
            $userTransfer->setLocaleName($localeTransfer->getLocaleName());

            return $userTransfer;
        }

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function expandUserCollectionWithLocale(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        if (!$this->isMissingLocaleData($userCollectionTransfer)) {
            return $userCollectionTransfer;
        }

        $availableLocales = $this->localeFacade->getAvailableLocales();
        $currentLocaleTransfer = $this->localeFacade->getCurrentLocale();
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            if (!$userTransfer->getFkLocale() && !$userTransfer->getLocaleName()) {
                $userTransfer
                    ->setLocaleName($currentLocaleTransfer->getLocaleNameOrFail())
                    ->setFkLocale($currentLocaleTransfer->getIdLocaleOrFail());

                continue;
            }

            if (!$userTransfer->getFkLocale()) {
                $idLocale = array_search($userTransfer->getLocaleNameOrFail(), $availableLocales, true) ?: null;

                $userTransfer->setFkLocale($idLocale);
            }

            if (!$userTransfer->getLocaleName()) {
                $localeName = $availableLocales[$userTransfer->getFkLocaleOrFail()] ?? null;

                $userTransfer->setLocaleName($localeName);
            }
        }

        return $userCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return bool
     */
    protected function isMissingLocaleData(UserCollectionTransfer $userCollectionTransfer): bool
    {
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            if (!$userTransfer->getFkLocale() || !$userTransfer->getLocaleName()) {
                return true;
            }
        }

        return false;
    }
}
