<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

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
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductAvailabilityByAbstractProductSku(string $abstractProductSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productAvailabilitiesResource
            ->findAbstractProductAvailabilityByAbstractProductSku($abstractProductSku, $restRequest);
    }

    /**
     * @param string $concreteProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductAvailabilityByConcreteProductSku(string $concreteProductSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productAvailabilitiesResource
            ->findConcreteProductAvailabilityByConcreteProductSku($concreteProductSku, $restRequest);
    }
}
