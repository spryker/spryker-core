<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;

class ProductConfigurationCartItemExpander implements ProductConfigurationCartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface
     */
    protected $productConfigurationInstanceMapper;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(
        ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper,
        ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient
    ) {
        $this->productConfigurationInstanceMapper = $productConfigurationInstanceMapper;
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function expandWithProductConfigurationInstance(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        $restCartItemProductConfigurationInstanceAttributesTransfer = $restCartItemsAttributesTransfer->getProductConfigurationInstance();

        if ($restCartItemProductConfigurationInstanceAttributesTransfer) {
            $productConfigurationInstanceTransfer = $this->productConfigurationInstanceMapper
               ->mapRestCartItemProductConfigurationToProductConfigurationInstance(
                   $restCartItemProductConfigurationInstanceAttributesTransfer,
                   new ProductConfigurationInstanceTransfer()
               );

            return $cartItemRequestTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceBySku($restCartItemsAttributesTransfer->getSkuOrFail());

        if (!$productConfigurationInstanceTransfer) {
            return $cartItemRequestTransfer;
        }

        return $cartItemRequestTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}
