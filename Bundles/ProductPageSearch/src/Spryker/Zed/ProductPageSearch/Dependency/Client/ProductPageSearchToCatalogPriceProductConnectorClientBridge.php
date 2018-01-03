<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Client;

use Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorClientInterface;

class ProductPageSearchToCatalogPriceProductConnectorClientBridge implements ProductPageSearchToCatalogPriceProductConnectorClientInterface
{

    /**
     * @var CatalogPriceProductConnectorClientInterface
     */
    protected $catalogPriceProductConnectorClient;

    /**
     * @param CatalogPriceProductConnectorClientInterface $catalogPriceProductConnectorClient
     */
    public function __construct($catalogPriceProductConnectorClient)
    {
        $this->catalogPriceProductConnectorClient = $catalogPriceProductConnectorClient;
    }

    /**
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return string
     */
    public function buildPricedIdentifierFor($priceType, $currencyIsoCode, $priceMode)
    {
        return $this->catalogPriceProductConnectorClient->buildPricedIdentifierFor($priceType, $currencyIsoCode, $priceMode);
    }

}
