<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ContentGuiToLocaleFacadeBridge implements ContentGuiToLocaleFacadeInterface
{
    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection(): array
    {
        return $this->localeFacade->getLocaleCollection();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getAvailableLocales(): array
    {
        return $this->localeFacade->getAvailableLocales();
    }
}
