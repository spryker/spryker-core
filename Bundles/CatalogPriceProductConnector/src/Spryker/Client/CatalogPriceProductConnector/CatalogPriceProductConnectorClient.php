<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class CatalogPriceProductConnectorClient extends AbstractClient implements CatalogPriceProductConnectorClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function buildPriceIdentifierForCurrentCurrency()
    {
        return $this->getFactory()->createPriceIdentifierBuilder()->buildIdentifierForCurrentCurrency();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return string
     */
    public function buildPricedIdentifierFor($priceType, $currencyIsoCode, $priceMode)
    {
        return $this->getFactory()->createPriceIdentifierBuilder()->buildIdentifierFor($priceType, $currencyIsoCode, $priceMode);
    }
}
