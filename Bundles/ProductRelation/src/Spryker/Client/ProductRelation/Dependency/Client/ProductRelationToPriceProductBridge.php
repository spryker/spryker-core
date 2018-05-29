<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

class ProductRelationToPriceProductBridge implements ProductRelationToPriceProductInterface
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
     * @param array $defaultPriceMap
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductAbstractPriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract): CurrentProductPriceTransfer
    {
        if (!method_exists($this->priceProductClient, 'resolveProductAbstractPriceByPriceDimension')) {
            return $this->priceProductClient->resolveProductPrice($defaultPriceMap);
        }

        return $this->priceProductClient->resolveProductAbstractPriceByPriceDimension($defaultPriceMap, $idProductAbstract);
    }
}
