<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Price;

interface PriceIdentifierBuilderInterface
{
    /**
     * @return string
     */
    public function buildIdentifierForCurrentCurrency();

    /**
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return string
     */
    public function buildIdentifierFor($priceType, $currencyIsoCode, $priceMode);
}
