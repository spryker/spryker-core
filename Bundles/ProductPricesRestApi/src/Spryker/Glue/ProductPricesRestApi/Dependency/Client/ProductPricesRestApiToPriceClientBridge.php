<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Dependency\Client;

class ProductPricesRestApiToPriceClientBridge implements ProductPricesRestApiToPriceClientInterface
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
    public function getCurrentPriceMode(): string
    {
        return $this->priceClient->getCurrentPriceMode();
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier(): string
    {
        return $this->priceClient->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier(): string
    {
        return $this->priceClient->getNetPriceModeIdentifier();
    }
}
