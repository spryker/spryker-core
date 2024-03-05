<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Language;

use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface;

class LanguageNegotiation implements LanguageNegotiationInterface
{
    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface
     */
    protected GlueStorefrontApiApplicationToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface
     */
    protected GlueStorefrontApiApplicationToLocaleServiceInterface $localeService;

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface $storeClient
     * @param \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface $localeService
     */
    public function __construct(
        GlueStorefrontApiApplicationToStoreClientInterface $storeClient,
        GlueStorefrontApiApplicationToLocaleServiceInterface $localeService
    ) {
        $this->storeClient = $storeClient;
        $this->localeService = $localeService;
    }

    /**
     * @param string $acceptLanguage
     *
     * @return string
     */
    public function getLanguageIsoCode(string $acceptLanguage): string
    {
        $storeTransfer = $this->storeClient->getCurrentStore();
        $storeLocaleCodes = $storeTransfer->getAvailableLocaleIsoCodes();
        if ($this->storeClient->isDynamicStoreEnabled()) {
            $storeLocaleCodes = $this->getLocaleCodesIndexedByLanguageCode($storeLocaleCodes);
        }

        if (!$acceptLanguage) {
            return array_shift($storeLocaleCodes);
        }

        $acceptLanguageTransfer = $this->localeService->getAcceptLanguage($acceptLanguage, array_keys($storeLocaleCodes));

        if (!$acceptLanguageTransfer || $acceptLanguageTransfer->getType() === null) {
            return array_shift($storeLocaleCodes);
        }

        if (!isset($storeLocaleCodes[$acceptLanguageTransfer->getType()])) {
            return array_shift($storeLocaleCodes);
        }

        return $storeLocaleCodes[$acceptLanguageTransfer->getType()];
    }

    /**
     * @param list<string> $localeCodes
     *
     * @return array<string, string>
     */
    protected function getLocaleCodesIndexedByLanguageCode(array $localeCodes): array
    {
        $indexedLocaleCodes = [];
        foreach ($localeCodes as $localeCode) {
            $indexedLocaleCodes[$this->extractLanguageCode($localeCode)] = $localeCode;
        }

        return $indexedLocaleCodes;
    }

    /**
     * @param string $localeCode
     *
     * @return string
     */
    protected function extractLanguageCode(string $localeCode): string
    {
        $localeCodeParts = explode('_', $localeCode);

        return $localeCodeParts[0];
    }
}
