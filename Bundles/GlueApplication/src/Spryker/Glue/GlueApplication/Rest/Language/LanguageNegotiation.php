<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Language;

use Negotiation\AbstractNegotiator;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;

class LanguageNegotiation implements LanguageNegotiationInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Negotiation\Negotiator
     */
    protected $negotiator;

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface $storeClient
     * @param \Negotiation\AbstractNegotiator $negotiator
     */
    public function __construct(GlueApplicationToStoreClientInterface $storeClient, AbstractNegotiator $negotiator)
    {
        $this->storeClient = $storeClient;
        $this->negotiator = $negotiator;
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
            return array_shift($storeLocaleCodes);
        }

        $language = $this->negotiator->getBest($acceptLanguage, array_keys($storeLocaleCodes));
        if (!$language) {
            return array_shift($storeLocaleCodes);
        }

        return $storeLocaleCodes[$language->getType()];
    }
}
