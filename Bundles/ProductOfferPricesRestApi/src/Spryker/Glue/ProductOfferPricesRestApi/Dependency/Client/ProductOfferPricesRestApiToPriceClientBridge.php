<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client;

class ProductOfferPricesRestApiToPriceClientBridge implements ProductOfferPricesRestApiToPriceClientInterface
{
    /**
     * @var \Spryker\Client\Price\PriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Client\Price\PriceClientInterface $priceClient
     */
    public function __construct($priceClient)
    {
        $this->priceClient = $priceClient;
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->priceClient->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->priceClient->getNetPriceModeIdentifier();
    }
}
