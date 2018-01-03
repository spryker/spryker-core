<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Client;

use Spryker\Client\PriceProduct\PriceProductClientInterface;

class PriceProductStorageToPriceProductBridge implements PriceProductStorageToPriceProductInterface
{

    /**
     * @var PriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param PriceProductClientInterface $priceProductClient
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
}
