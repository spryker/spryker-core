<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale\Reader;

use Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientInterface;

class LocaleReader implements LocaleReaderInterface
{
    /**
     * @var \Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    protected LocaleToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\Locale\Reader\LanguageReaderInterface
     */
    protected LanguageReaderInterface $languageReader;

    /**
     * @param \Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientInterface $storeClient
     * @param \Spryker\Client\Locale\Reader\LanguageReaderInterface $languageReader
     */
    public function __construct(LocaleToStoreClientInterface $storeClient, LanguageReaderInterface $languageReader)
    {
        $this->storeClient = $storeClient;
        $this->languageReader = $languageReader;
    }

    /**
     * @return array<string, string>
     */
    public function getLocaleList(): array
    {
        $indexedLocales = [];

        foreach ($this->storeClient->getCurrentStore()->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $indexedLocales[$this->languageReader->getLanguageByLocaleCode($localeIsoCode)] = $localeIsoCode;
        }

        return $indexedLocales;
    }
}
