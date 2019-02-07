<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\UserExpander;

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

        if (!$idLocale && $localeName) {
            $localeTransfer = $this->localeFacade->getLocale($localeName);
            $userTransfer->setFkLocale($localeTransfer->getIdLocale())
                ->setLocaleName($localeName);

            return $userTransfer;
        }

        if ($idLocale && !$localeName) {
            $localeTransfer = $this->localeFacade->getLocaleById($idLocale);
            $userTransfer->setLocaleName($localeTransfer->getLocaleName());

            return $userTransfer;
        }

        return $userTransfer;
    }
}
