<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource;

class ProductsResourceRelationToProductAvailabilitiesRestApiBridge implements ProductsResourceRelationToProductAvailabilitiesRestApiInterface
{
    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiResourceInterface
     */
    protected $productAvailabilitiesResource;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiResourceInterface $productsAvailabilityResource
     */
    public function __construct($productsAvailabilityResource)
    {
        $this->productAvailabilitiesResource = $productsAvailabilityResource;
    }

    /**
     * @param string $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function findProductAbstractAvailabilityByAbstractProductId($abstractProductId, $restRequest)
    {
        return $this->productAvailabilitiesResource
            ->findAbstractProductAvailabilityByAbstractProductId($abstractProductId, $restRequest);
    }

    /**
     * @param string $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function findConcreteProductAvailabilityByConcreteProductId($concreteProductId, $restRequest)
    {
        return $this->productAvailabilitiesResource
            ->findConcreteProductAvailabilityByConcreteProductId($concreteProductId, $restRequest);
    }
}
