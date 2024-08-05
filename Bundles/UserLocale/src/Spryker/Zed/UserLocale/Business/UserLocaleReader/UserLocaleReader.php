<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\UserLocaleReader;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToStoreFacadeInterface;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface;

class UserLocaleReader implements UserLocaleReaderInterface
{
    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface
     */
    protected UserLocaleToUserFacadeBridgeInterface $userFacade;

    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface
     */
    protected UserLocaleToLocaleFacadeBridgeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToStoreFacadeInterface
     */
    protected UserLocaleToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToUserFacadeBridgeInterface $userFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface $localeFacade
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        UserLocaleToUserFacadeBridgeInterface $userFacade,
        UserLocaleToLocaleFacadeBridgeInterface $localeFacade,
        UserLocaleToStoreFacadeInterface $storeFacade
    ) {
        $this->userFacade = $userFacade;
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentUserLocaleTransfer(): LocaleTransfer
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return $this->getCurrentLocale();
        }

        return $this->getLocaleTransferByLocaleName($this->getCurrentUser()->getLocaleName());
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransferByLocaleName(string $localeName): LocaleTransfer
    {
        return $this->localeFacade->getLocale($localeName);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getCurrentUser(): UserTransfer
    {
        return $this->userFacade->getCurrentUser();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        /**
         * Required by infrastructure, exists only for BC reasons with DMS mode.
         */
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->localeFacade->getCurrentLocale();
        }

        $supportedLocaleCodes = $this->localeFacade->getSupportedLocaleCodes();
        /** @phpstan-var string */
        $supportedLocaleCode = reset($supportedLocaleCodes);

        return $this->localeFacade->getLocale($supportedLocaleCode);
    }
}
