<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Language;

use Negotiation\AcceptLanguage;
use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface;

class LanguageNegotiation implements LanguageNegotiationInterface
{
    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Negotiation\LanguageNegotiator
     */
    protected $negotiator;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface $storeFacade
     * @param \Negotiation\LanguageNegotiator $negotiator
     */
    public function __construct(GlueBackendApiApplicationToStoreFacadeInterface $storeFacade, LanguageNegotiator $negotiator)
    {
        $this->storeFacade = $storeFacade;
        $this->negotiator = $negotiator;
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

        $language = $this->findAcceptedLanguage($acceptLanguage, $storeLocaleCodes);
        if (!$language) {
            return array_shift($storeLocaleCodes);
        }

        return $storeLocaleCodes[$language->getType()];
    }

    /**
     * @param string $acceptLanguage
     * @param array<string, string> $storeLocaleCodes
     *
     * @return \Negotiation\AcceptLanguage|null
     */
    protected function findAcceptedLanguage(string $acceptLanguage, array $storeLocaleCodes): ?AcceptLanguage
    {
        /** @var \Negotiation\AcceptLanguage $acceptedLanguage */
        $acceptedLanguage = $this->negotiator->getBest($acceptLanguage, array_keys($storeLocaleCodes));

        return $acceptedLanguage;
    }
}
