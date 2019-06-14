<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication\FormExpander\DataProvider;

use Spryker\Zed\UserLocaleGui\Communication\FormExpander\UserLocaleFormExpander;
use Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleFacadeBridgeInterface;

class LocaleChoiceFormDataProvider
{
    /**
     * @var \Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleFacadeBridgeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\UserLocaleGui\Dependency\Facade\UserLocaleGuiToLocaleFacadeBridgeInterface $localeFacade
     */
    public function __construct(UserLocaleGuiToLocaleFacadeBridgeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            UserLocaleFormExpander::OPTIONS_LOCALE => $this->getLocales(),
        ];
    }

    /**
     * @return array
     */
    protected function getLocales(): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $options = [];

        foreach ($localeTransfers as $localeTransfer) {
            $options[$localeTransfer->getLocaleName()] = $localeTransfer->getIdLocale();
        }

        return $options;
    }
}
