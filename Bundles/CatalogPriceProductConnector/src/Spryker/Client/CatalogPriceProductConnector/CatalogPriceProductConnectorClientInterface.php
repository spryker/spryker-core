<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
interface CatalogPriceProductConnectorClientInterface
{
    /**
     * Specification:
     *  - Builds price identifier which used in Elasticsearch queries, identifier contains price type, currency code, price mode
     *  - Builds for currently selected Currency, PriceMode and PriceType if any of those are not selected then uses default values.
     *
     * @api
     *
     * @return string
     */
    public function buildPriceIdentifierForCurrentCurrency();

    /**
     * Specification:
     *  - Builds identifier ∂∂from given values.
     *
     * @api
     *
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return string
     */
    public function buildPricedIdentifierFor($priceType, $currencyIsoCode, $priceMode);
}
