<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface;

class ProductConfigurationCartItemExpander implements ProductConfigurationCartItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstanceMapperInterface
     */
    protected ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    protected ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

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
                   new ProductConfigurationInstanceTransfer(),
               );

            return $cartItemRequestTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        $productConfigurationInstanceTransfer = $this->findProductConfigurationInstance($cartItemRequestTransfer);

        return $cartItemRequestTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstance(
        CartItemRequestTransfer $cartItemRequestTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceCollectionTransfer = $this->getProductConfigurationInstanceCollection($cartItemRequestTransfer);

        if (!$productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count()) {
            return null;
        }

        return $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    protected function getProductConfigurationInstanceCollection(
        CartItemRequestTransfer $cartItemRequestTransfer
    ): ProductConfigurationInstanceCollectionTransfer {
        $productConfigurationInstanceConditionsTransfer = (new ProductConfigurationInstanceConditionsTransfer())
            ->addSku($cartItemRequestTransfer->getSkuOrFail());

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaTransfer())
            ->setProductConfigurationInstanceConditions($productConfigurationInstanceConditionsTransfer);

        return $this->productConfigurationStorageClient
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);
    }
}
