<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
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
     * @param int $idProductAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductAbstractById(int $idProductAbstract, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productsRestApiResource->findProductAbstractById($idProductAbstract, $restRequest);
    }
}
