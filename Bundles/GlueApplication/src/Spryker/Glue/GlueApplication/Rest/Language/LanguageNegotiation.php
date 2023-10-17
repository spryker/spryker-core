<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Language;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class LanguageNegotiation implements LanguageNegotiationInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    protected GlueApplicationToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface
     */
    protected GlueApplicationToLocaleServiceInterface $localeService;

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface $storeClient
     * @param \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface $localeService
     */
    public function __construct(
        GlueApplicationToStoreClientInterface $storeClient,
        GlueApplicationToLocaleServiceInterface $localeService
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

        if (!$acceptLanguage) {
            return $this->getDefaultLocaleCode($storeTransfer, $storeLocaleCodes);
        }

        $acceptLanguageTransfer = $this->localeService->getAcceptLanguage($acceptLanguage, array_keys($storeLocaleCodes));

        if (!$acceptLanguageTransfer || $acceptLanguageTransfer->getType() === null) {
            return $this->getDefaultLocaleCode($storeTransfer, $storeLocaleCodes);
        }

        if (!isset($storeLocaleCodes[$acceptLanguageTransfer->getType()])) {
            return $this->getDefaultLocaleCode($storeTransfer, $storeLocaleCodes);
        }

        return $storeLocaleCodes[$acceptLanguageTransfer->getType()];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<string> $storeLocaleCodes
     *
     * @return string
     */
    protected function getDefaultLocaleCode(StoreTransfer $storeTransfer, array $storeLocaleCodes): string
    {
        if (!$this->storeClient->isDynamicStoreEnabled()) {
            return array_shift($storeLocaleCodes);
        }

        return $storeTransfer->getDefaultLocaleIsoCode();
    }
}
