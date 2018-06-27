<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

class PriceProductStorageToPriceProductBridge implements PriceProductStorageToPriceProductInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\PriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProduct\PriceProductClientInterface $priceProductClient
     */
    public function __construct($priceProductClient)
    {
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPrice(array $priceMap)
    {
        return $this->priceProductClient->resolveProductPrice($priceMap);
    }

    /**
     * @param array $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransfer(array $priceProductTransfers): CurrentProductPriceTransfer
    {
        return $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);
    }
}
