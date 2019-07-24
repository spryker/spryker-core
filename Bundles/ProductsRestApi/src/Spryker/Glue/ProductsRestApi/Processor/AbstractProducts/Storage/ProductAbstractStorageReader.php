<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\Storage;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Locale\LocaleClient;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ProductAbstractStorageReader implements ProductAbstractStorageReaderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductsRestApiToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer|null
     */
    public function provideResourceIdentifierByUrlStorageTransfer(UrlStorageTransfer $urlStorageTransfer): ?ResourceIdentifierTransfer
    {
        $data = $this->productStorageClient->findProductAbstractStorageData(
            $urlStorageTransfer->getFkResourceProductAbstract(),
            (new LocaleClient())->getCurrentLocale() // TODO: change this to pulling the locale from the RestRequest.
        );

        if (!$data || !$data[static::KEY_SKU]) {
            return null;
        }

        return (new ResourceIdentifierTransfer())
            ->setType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS)
            ->setId($data[static::KEY_SKU]);
    }
}
