<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Dependency\Client;

class MerchantProductStorageToProductStorageClientBridge implements MerchantProductStorageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array<mixed>
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName)
    {
        return $this->productStorageClient->getProductAbstractStorageData($idProductAbstract, $localeName);
    }
}
