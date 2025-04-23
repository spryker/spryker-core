<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Language;

use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface;

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
        /** @phpstan-var string $defaultLocaleIsoCode */
        $defaultLocaleIsoCode = $storeTransfer->getDefaultLocaleIsoCode() ?? current($storeLocaleCodes);

        if ($acceptLanguage === '') {
            return $defaultLocaleIsoCode;
        }

        $acceptLanguageTransfer = $this->localeService->getAcceptLanguage($acceptLanguage, array_keys($storeLocaleCodes));

        if (!$acceptLanguageTransfer || $acceptLanguageTransfer->getType() === null) {
            return $defaultLocaleIsoCode;
        }

        if (!isset($storeLocaleCodes[$acceptLanguageTransfer->getType()])) {
            return $defaultLocaleIsoCode;
        }

        return $storeLocaleCodes[$acceptLanguageTransfer->getType()];
    }
}
