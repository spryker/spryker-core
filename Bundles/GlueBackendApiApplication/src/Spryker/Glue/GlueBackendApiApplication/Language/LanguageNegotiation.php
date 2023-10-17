<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Language;

use Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface;
use Spryker\Glue\GlueBackendApiApplication\Dependency\Service\GlueBackendApiApplicationToLocaleServiceInterface;

class LanguageNegotiation implements LanguageNegotiationInterface
{
    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface
     */
    protected GlueBackendApiApplicationToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Dependency\Service\GlueBackendApiApplicationToLocaleServiceInterface
     */
    protected GlueBackendApiApplicationToLocaleServiceInterface $localeService;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface $storeFacade
     * @param \Spryker\Glue\GlueBackendApiApplication\Dependency\Service\GlueBackendApiApplicationToLocaleServiceInterface $localeService
     */
    public function __construct(
        GlueBackendApiApplicationToStoreFacadeInterface $storeFacade,
        GlueBackendApiApplicationToLocaleServiceInterface $localeService
    ) {
        $this->storeFacade = $storeFacade;
        $this->localeService = $localeService;
    }

    /**
     * @param string $acceptLanguage
     *
     * @return string
     */
    public function getLanguageIsoCode(string $acceptLanguage): string
    {
        $storeTransfer = $this->storeFacade->getCurrentStore(true);
        $storeLocaleCodes = $storeTransfer->getAvailableLocaleIsoCodes();

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
}
