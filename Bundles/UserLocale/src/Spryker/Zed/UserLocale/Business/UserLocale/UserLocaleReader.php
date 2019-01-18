<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Business\UserLocale;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface;
use Spryker\Zed\UserLocale\UserLocaleConfig;

class UserLocaleReader implements UserLocaleReaderInterface
{
    /**
     * @var \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\UserLocale\UserLocaleConfig
     */
    protected $userLocaleConfig;

    /**
     * @param \Spryker\Zed\UserLocale\Dependency\Facade\UserLocaleToLocaleFacadeBridgeInterface $localeFacade
     * @param \Spryker\Zed\UserLocale\UserLocaleConfig $userLocaleConfig
     */
    public function __construct(
        UserLocaleToLocaleFacadeBridgeInterface $localeFacade,
        UserLocaleConfig $userLocaleConfig
    ) {
        $this->localeFacade = $localeFacade;
        $this->userLocaleConfig = $userLocaleConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getDefaultUserLocale(): LocaleTransfer
    {
        $localeName = $this->userLocaleConfig->getDefaultLocaleName();

        return $this->localeFacade->getLocale($localeName);
    }
}
