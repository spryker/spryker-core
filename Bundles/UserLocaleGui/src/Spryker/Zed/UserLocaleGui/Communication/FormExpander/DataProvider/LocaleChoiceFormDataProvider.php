<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocaleGui\Communication\FormExpander\DataProvider;

use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
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
     * @return array<string, mixed>
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
        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
            ->setLocaleConditions(
                (new LocaleConditionsTransfer())
                    ->setLocaleNames($this->localeFacade->getSupportedLocaleCodes()),
            );

        $localeTransfers = $this->localeFacade->getLocaleCollection($localeCriteriaTransfer);

        $options = [];

        foreach ($localeTransfers as $localeTransfer) {
            $options[$localeTransfer->getLocaleName()] = $localeTransfer->getIdLocale();
        }

        return $options;
    }
}
