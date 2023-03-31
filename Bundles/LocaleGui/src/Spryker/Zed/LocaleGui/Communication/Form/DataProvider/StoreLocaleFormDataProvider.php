<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\LocaleGui\Communication\Form\StoreLocaleForm;
use Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToLocaleFacadeInterface;

class StoreLocaleFormDataProvider
{
    /**
     * @var \Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(LocaleGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<array<string>>
     */
    public function getOptions(StoreTransfer $storeTransfer): array
    {
        $allLocaleChoices = $this->getAllLocaleChoices();
        $availableLocaleIsoCodes = $storeTransfer->getAvailableLocaleIsoCodes();

        $defaultLocaleChoices = [];
        foreach ($availableLocaleIsoCodes as $availableLocaleIsoCode) {
            $defaultLocaleChoices[$availableLocaleIsoCode] = $availableLocaleIsoCode;
        }

        return [
            StoreLocaleForm::OPTION_LOCALE_CHOICES => $defaultLocaleChoices ?: $allLocaleChoices,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getAllLocaleChoices(): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection(new LocaleCriteriaTransfer());

        $localeChoices = [];
        foreach ($localeTransfers as $localeTransfer) {
            $localeChoices[$localeTransfer->getLocaleNameOrFail()] = $localeTransfer->getLocaleNameOrFail();
        }

        return $localeChoices;
    }
}
