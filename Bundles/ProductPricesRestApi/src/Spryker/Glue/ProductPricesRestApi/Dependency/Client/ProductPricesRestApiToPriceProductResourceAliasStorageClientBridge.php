<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Dependency\Client;

class ProductPricesRestApiToPriceProductResourceAliasStorageClientBridge implements ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\PriceProductResourceAliasStorage\PriceProductResourceAliasStorageClientInterface
     */
    protected $priceProductResourceAliasStorageClient;

    /**
     * @param \Spryker\Client\PriceProductResourceAliasStorage\PriceProductResourceAliasStorageClientInterface $priceProductResourceAliasStorageClient
     */
    public function __construct($priceProductResourceAliasStorageClient)
    {
        $this->priceProductResourceAliasStorageClient = $priceProductResourceAliasStorageClient;
    }

    /**
     * @param string $abstractProductSku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductAbstractStorageTransfer($abstractProductSku)
    {
        return $this->priceProductResourceAliasStorageClient->findPriceProductAbstractStorageTransfer($abstractProductSku);
    }

    /**
     * @param string $concreteProductSku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductConcreteStorageTransfer($concreteProductSku)
    {
        return $this->priceProductResourceAliasStorageClient->findPriceProductConcreteStorageTransfer($concreteProductSku);
    }
}
