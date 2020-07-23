<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceBridge implements ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct($productsRestApiResource)
    {
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param int[] $productConcreteIds
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductConcreteCollectionByIds(array $productConcreteIds, RestRequestInterface $restRequest): array
    {
        return $this->productsRestApiResource->getProductConcreteCollectionByIds($productConcreteIds, $restRequest);
    }
}
