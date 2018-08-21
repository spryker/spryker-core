<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource;

class ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiBridge implements ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiResourceInterface
     */
    protected $productImageSetsRestApiResource;

    /**
     * @param \Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiResourceInterface $productImageSetsRestApiResource
     */
    public function __construct($productImageSetsRestApiResource)
    {
        $this->productImageSetsRestApiResource = $productImageSetsRestApiResource;
    }

    /**
     * @param string $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductImageSetsByAbstractProductId($abstractProductId, $restRequest)
    {
        return $this->productImageSetsRestApiResource
            ->findAbstractProductImageSetsByAbstractProductId($abstractProductId, $restRequest);
    }

    /**
     * @param string $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductImageSetsByConcreteProductId($concreteProductId, $restRequest)
    {
        return $this->productImageSetsRestApiResource
            ->findConcreteProductImageSetsByConcreteProductId($concreteProductId, $restRequest);
    }
}
