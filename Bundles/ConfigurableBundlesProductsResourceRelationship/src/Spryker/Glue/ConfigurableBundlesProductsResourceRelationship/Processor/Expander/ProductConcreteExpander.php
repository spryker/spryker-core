<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface
     */
    protected $productConcreteReader;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface $productConcreteReader
     * @param \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        ProductConcreteReaderInterface $productConcreteReader,
        ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->productConcreteReader = $productConcreteReader;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $configurableBundleTemplateSlotStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateSlotStorageTransfer instanceof ConfigurableBundleTemplateSlotStorageTransfer) {
                continue;
            }

            $productConcreteIds = $this->productConcreteReader
                ->getProductConcreteIdsByProductListId(
                    $configurableBundleTemplateSlotStorageTransfer->getIdProductList()
                );

            $productConcreteResources = $this->productsRestApiResource
                ->getProductConcretesByIds($productConcreteIds, $restRequest);

            foreach ($productConcreteResources as $productConcreteResource) {
                $resource->addRelationship($productConcreteResource);
            }
        }
    }
}
