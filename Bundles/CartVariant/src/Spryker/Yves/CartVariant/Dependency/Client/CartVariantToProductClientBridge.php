<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartVariant\Dependency\Client;

class CartVariantToProductClientBridge implements CartVariantToProductClientBridgeInterface
{
    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $client;

    /**
     * @param \Spryker\Client\Product\ProductClientInterface $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract)
    {
        return $this->client->getAttributeMapByIdProductAbstractForCurrentLocale($idProductAbstract);
    }
}
