<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Dependency\Client;

class ProductImageStorageToProductImageClientBridge implements ProductImageStorageToProductImageClientInterface
{
    /**
     * @var \Spryker\Client\ProductImage\ProductImageClientInterface
     */
    protected $productImageClient;

    /**
     * @param \Spryker\Client\ProductImage\ProductImageClientInterface $productImageClient
     */
    public function __construct($productImageClient)
    {
        $this->productImageClient = $productImageClient;
    }

    /**
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool
    {
        return $this->productImageClient->isProductImageAlternativeTextEnabled();
    }
}
