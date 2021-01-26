<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Generated\Shared\Transfer\RestProductConfigurationInstanceAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;

class ProductConfigurationProductConcreteExpander implements ProductConfigurationProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function expandWithProductConfigurationInstance(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
    ): ConcreteProductsRestAttributesTransfer {
        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceBySku($concreteProductsRestAttributesTransfer->getSkuOrFail());
        if (!$productConfigurationInstanceTransfer) {
            return $concreteProductsRestAttributesTransfer;
        }
        $restProductConfigurationInstanceAttributesTransfer = (new RestProductConfigurationInstanceAttributesTransfer())
            ->fromArray($productConfigurationInstanceTransfer->toArray(), true);

        $concreteProductsRestAttributesTransfer->setProductConfigurationInstance($restProductConfigurationInstanceAttributesTransfer);

        return $concreteProductsRestAttributesTransfer;
    }
}
