<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Negotiator;

use Exception;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Service\Locale\LocaleServiceInterface;

class LanguageNegotiator implements LanguageNegotiatorInterface
{
    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
     */
    protected LocaleClientInterface $localeClient;

    /**
     * @var \Spryker\Service\Locale\LocaleServiceInterface
     */
    protected LocaleServiceInterface $localeService;

    /**
     * @var \Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    protected LocaleToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\Locale\LocaleClientInterface $localeClient
     * @param \Spryker\Service\Locale\LocaleServiceInterface $localeService
     * @param \Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface $storeClient
     */
    public function __construct(
        LocaleClientInterface $localeClient,
        LocaleServiceInterface $localeService,
        LocaleToStoreClientInterface $storeClient
    ) {
        $this->localeClient = $localeClient;
        $this->localeService = $localeService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string|null $headerAcceptLanguage
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getLanguageIsoCode(?string $headerAcceptLanguage = null): string
    {
        $storeLocaleCodes = $this->localeClient->getLocales();

        if ($storeLocaleCodes === []) {
            throw new Exception('Unable to get locale codes by current store.');
        }

        if (!$headerAcceptLanguage) {
            return $this->getDefaultLanguage($storeLocaleCodes);
        }

        foreach ($storeLocaleCodes as $localeName) {
            if ($localeName === $headerAcceptLanguage) {
                return $localeName;
            }
        }

        $acceptLanguageTransfer = $this->localeService->getAcceptLanguage($headerAcceptLanguage, array_keys($storeLocaleCodes));

        if (!$acceptLanguageTransfer || $acceptLanguageTransfer->getType() === null) {
            return $this->getDefaultLanguage($storeLocaleCodes);
        }

        if (!isset($storeLocaleCodes[$acceptLanguageTransfer->getType()])) {
            return $this->getDefaultLanguage($storeLocaleCodes);
        }

        return $storeLocaleCodes[$acceptLanguageTransfer->getType()];
    }

    /**
     * @param array<string, string> $storeLocaleCodes
     *
     * @return string
     */
    protected function getDefaultLanguage(array $storeLocaleCodes): string
    {
        return $this->storeClient->getCurrentStore()->getDefaultLocaleIsoCode() ?? (string)array_shift($storeLocaleCodes);
    }
}
