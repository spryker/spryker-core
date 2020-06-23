<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductAbstractListsRestApiToProductsRestApiResourceBridge implements ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
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
     * @param int[] $ids
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductAbstractsByIds(array $ids, RestRequestInterface $restRequest, string $storeName): array
    {
        return $this->productsRestApiResource->getProductAbstractsByIds($ids, $restRequest, $storeName);
    }
}
